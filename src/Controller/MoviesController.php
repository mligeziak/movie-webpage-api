<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Network\Http\Client;
use Cake\Event\Event;

/**
 * Movies Controller
 *
 * @property \App\Model\Table\MoviesTable $Movies
 *
 * @method \App\Model\Entity\Movie[] paginate($object = null, array $settings = [])
 */
class MoviesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['add', 'getMovieByImdbid', 'search']);

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Security');
    }

    public function beforeFilter(Event $event)
    {
        if (in_array($this->request->action, ['add', 'getMovieByImdbid', 'search'])) {
            $this->response->header('Access-Control-Allow-Origin', '*');
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $movies = $this->paginate($this->Movies);

        $this->set(compact('movies'));
        $this->set('_serialize', ['movies']);
    }

    /**
     * View method
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $movie = $this->Movies->get($id, [
            'contain' => []
        ]);

        $this->set('movie', $movie);
        $this->set('_serialize', ['movie']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $movie = $this->Movies->newEntity();
        if ($this->request->is('post')) {
            $movie = $this->Movies->patchEntity($movie, $this->request->getData());
            if ($this->Movies->save($movie)) {
                $this->Flash->success(__('The movie has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movie could not be saved. Please, try again.'));
        }
        $this->set(compact('movie'));
        $this->set('_serialize', ['movie']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $movie = $this->Movies->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $movie = $this->Movies->patchEntity($movie, $this->request->getData());
            if ($this->Movies->save($movie)) {
                $this->Flash->success(__('The movie has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The movie could not be saved. Please, try again.'));
        }
        $this->set(compact('movie'));
        $this->set('_serialize', ['movie']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Movie id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $movie = $this->Movies->get($id);
        if ($this->Movies->delete($movie)) {
            $this->Flash->success(__('The movie has been deleted.'));
        } else {
            $this->Flash->error(__('The movie could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getMovieByImdbid($imdbid = null)
    {
        $movie = '';
        $http = new Client();
        $response = $http->get('http://www.omdbapi.com/?i=' . $imdbid);

        if($response->isOk()) {
            $movieJson = $response->body;
            $movie = json_decode($movieJson, true);
        }

        $this->set('movie', $movie);
        $this->set('_serialize', ['movie']);
    }

    public function searchByTitleOmdb($title = '')
    {
        $movies = [];
        $http = new Client();
        $response = $http->get('http://www.omdbapi.com/?s=' . $title);

        if($response->isOk()) {
            $moviesJson = $response->body;
            $movies = json_decode($moviesJson, true);
        }

        if(isset($movies['Response']) && $movies['Response'] == 'True') {
            return $movies['Search'];
        }
        else {
            return [];
        }
    }

    public function search($title = '')
    {
        $movies = $this->searchByTitleOmdb($title);

        $this->set('movies', $movies);
        $this->set('_serialize', ['movies']);
    }
}
