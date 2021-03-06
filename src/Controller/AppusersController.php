<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Core\Configure;

/**
 * Appusers Controller
 *
 * @property \App\Model\Table\AppusersTable $Appusers
 *
 * @method \App\Model\Entity\Appuser[] paginate($object = null, array $settings = [])
 */
class AppusersController extends AppController
{
    public $components = ['Cookie'];

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['signup', 'login', 'getAccountData', 'addToFavorites', 'getMyFavorites', 'addMovieRating', 'getMovieRating']);
    }

    public function beforeFilter(Event $event)
    {
        if (in_array($this->request->action, ['signup', 'login', 'getAccountData', 'addToFavorites', 'getMyFavorites', 'addMovieRating', 'getMovieRating'])) {
            $this->response->header('Access-Control-Allow-Origin', Configure::read('APP_ORIGIN'));
            $this->response->header('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
            $this->response->header('Access-Control-Allow-Headers', 'Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
            $this->response->header('Access-Control-Allow-Credentials', 'true');
        }
        $this->Cookie->config([
            'expires' => '+2 days',
            'path' => '/'
        ]);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $appusers = $this->paginate($this->Appusers);

        $this->set(compact('appusers'));
        $this->set('_serialize', ['appusers']);
    }

    /**
     * View method
     *
     * @param string|null $id Appuser id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $appuser = $this->Appusers->get($id, [
            'contain' => []
        ]);

        $this->set('appuser', $appuser);
        $this->set('_serialize', ['appuser']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $appuser = $this->Appusers->newEntity();
        if ($this->request->is('post')) {
            $appuser = $this->Appusers->patchEntity($appuser, $this->request->getData());
            if ($this->Appusers->save($appuser)) {
                $this->Flash->success(__('The appuser has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appuser could not be saved. Please, try again.'));
        }
        $this->set(compact('appuser'));
        $this->set('_serialize', ['appuser']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Appuser id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $appuser = $this->Appusers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $appuser = $this->Appusers->patchEntity($appuser, $this->request->getData());
            if ($this->Appusers->save($appuser)) {
                $this->Flash->success(__('The appuser has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appuser could not be saved. Please, try again.'));
        }
        $this->set(compact('appuser'));
        $this->set('_serialize', ['appuser']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Appuser id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $appuser = $this->Appusers->get($id);
        if ($this->Appusers->delete($appuser)) {
            $this->Flash->success(__('The appuser has been deleted.'));
        } else {
            $this->Flash->error(__('The appuser could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function signup()
    {
        $appuser = $this->Appusers->newEntity();
        $saved = false;
        if ($this->request->is('post')) {
            $appuser = $this->Appusers->patchEntity($appuser, $this->request->getData());
            if ($this->Appusers->save($appuser)) {
                $saved = true;
            }
        }
        $this->set(compact('saved'));
        $this->set('_serialize', ['saved']);
    }

    public function login()
    {
        $hasher = new DefaultPasswordHasher();
        $loggedin = false;
        $verificationCode = '';
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $user = $this->Appusers->find('all', [
                'fields' => ['id', 'email', 'password'],
                'conditions' => ['Appusers.email' => $data['email']],
            ])->toArray();
            if(count($user) > 0) {
                $user = $user[0];
                if($hasher->check($data['password'], $user['password'])) {
                    $loggedin = true;
                    $this->Cookie->write('Appuser.verificationCode', $user['id']);
                }
            }
        }
        $verificationCode =  $this->Cookie->read('Appuser.verificationCode');
        $this->set(compact('loggedin', 'verificationCode'));
        $this->set('_serialize', ['loggedin', 'verificationCode']);
    }

    public function getAccountData()
    {
        $id =  $this->Cookie->read('Appuser.verificationCode');
        $appuser = $this->Appusers->get($id);

        $this->set('appuser', $appuser);
        $this->set('_serialize', ['appuser']);
    }

    public function addToFavorites($imdbid)
    {
        $saved = false;

        $data['appuser_id'] = $this->Cookie->read('Appuser.verificationCode');
        $data['imdbid'] = $imdbid;

        $this->loadmodel("Favorites");

        $favorite = $this->Favorites->newEntity();
        $favorite = $this->Favorites->patchEntity($favorite, $data);
        if($this->Favorites->save($favorite)) {
            $saved = true;
        }

        $this->set('saved', $saved);
        $this->set('_serialize', ['saved']);
    }

    public function getMyFavorites()
    {
        $id =  $this->Cookie->read('Appuser.verificationCode');
        $favorites = [];
        $this->loadmodel("Favorites");

        $results = $this->Favorites->find('all', [
            'conditions' => ['Favorites.appuser_id' => $id],
            'fields' => [
                'Movies.id',
                'Movies.title',
                'Movies.year',
                'Movies.imdbid',
                'Movies.type',
                'Movies.director',
                'Movies.poster',
                'Movies.genre',
                'Movies.plot'
            ]
        ])
        ->hydrate(false)
        ->join([
            'table' => 'movies',
            'alias' => 'Movies',
            'type' => 'INNER',
            'conditions' => 'Movies.imdbid = Favorites.imdbid',
        ])->toArray();

        foreach($results as $item) {
            $favorites[] = $item['Movies'];
        }

        $this->set('favorites', $favorites);
        $this->set('_serialize', ['favorites']);
    }

    public function addMovieRating($imdbid, $rating)
    {
        $saved = false;

        $data['rating'] = $rating;
        $data['imdbid'] = $imdbid;

        $this->loadmodel("Ratings");

        $newRating = $this->Ratings->newEntity();
        $newRating = $this->Ratings->patchEntity($newRating, $data);
        if($this->Ratings->save($newRating)) {
            $saved = true;
        }

        $this->set('saved', $saved);
        $this->set('_serialize', ['saved']);
    }

    public function getMovieRating($imdbid)
    {
        $this->loadmodel("Ratings");

        $rating = $this->Ratings->find('all', [
            'conditions' => ['Ratings.imdbid' => $imdbid]
        ]);
        $rating = $rating->select(['avg' => $rating->func()->avg('rating')])->toArray();

        if($rating[0]['avg'] == null) {
            $rating[0]['avg'] = 'no votes';
        }

        $this->set('rating', $rating);
        $this->set('_serialize', ['rating']);
    }
}
