<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Appusers Controller
 *
 * @property \App\Model\Table\AppusersTable $Appusers
 *
 * @method \App\Model\Entity\Appuser[] paginate($object = null, array $settings = [])
 */
class AppusersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['signup']);
    }

    public function beforeFilter(Event $event)
    {
        if (in_array($this->request->action, ['signup'])) {
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
        //if ($this->request->is('post')) {
            var_dump($this->request->getData());
            die();
            $appuser = $this->Appusers->patchEntity($appuser, $this->request->getData());
            if ($this->Appusers->save($appuser)) {
                $this->Flash->success(__('The appuser has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The appuser could not be saved. Please, try again.'));
        //}
        $this->set(compact('appuser'));
        $this->set('_serialize', ['appuser']);
    }
}
