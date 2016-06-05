<?php
class UsersController extends AppController {
	public $helpers = array('Html', 'Js');
    var $name = 'Users';
    public function beforeFilter() {
        parent::beforeFilter();
        // Allow users to register and logout.
        $this->Auth->allow('add', 'logout');
    }
    public function login() {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirect());
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }
    
    public function test($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->findById($id);
        $hits = $user['User']['hits'];
        $newhits = $hits + 1;
        $this->User->updateAll(
            array('User.hits' => $newhits),
            array('User.id' => $id)
        );
        $this->set('user', $user);
    }
    
    public function logout() {
        $this->Session->setFlash(__('You have been logged out'));
        return $this->redirect($this->Auth->logout());
    }
    
    public function index() {
        $this->User->recursive = 0;
        $users = $this->User->find('all', array('group' => 'User.id'));
        $this->set('users', $users);
    }

    public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid user'));
        }
        $user = $this->User->findById($id);
        $hits = $user['User']['hits'];
        $newhits = $hits + 1;
        $this->User->updateAll(
            array('User.hits' => $newhits),
            array('User.id' => $id)
        );
        if (!$user) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $user);
    }
    
    public function userslist($star_id = null) {
        if (!$star_id) {
            throw new NotFoundException(__('Invalid Star'));
        }
        $users = $this->User->find('all', array(
            'conditions' => array('User.star_id' => $star_id),
            'group' => 'User.id'
            )
        );
        $this->set('users', $users);
    }
    public function search() {
        if ($this->request->is('post')) {
            $value = $this->request->data['User']['id'];
            $this->redirect(array('controller'=>'users', 'action'=>'view', $value));
        }
    }
    public function add() {
        $this->Star = ClassRegistry::init('Star');
        $stars = $this->Star->find('all', array('group' => 'Star.id'));
        $this->set('stars', $stars);
        $options = array();
        foreach ($stars as $star) {
            $name = $star['Star']['name'];
            $starid = $star['Star']['id'];
            $this->UserStarEdge = ClassRegistry::init('UserStarEdge');
            $current = $this->UserStarEdge->find('all', array(
                'conditions' => array('UserStarEdge.star_id' => $starid)
                )
            );
            $current = array_filter(array_merge(array(0), $current));
            $capacity = count($current);
            if ($capacity < 11) {
                array_push($options, $name);
            }
        }
        $options = array_filter(array_merge(array(0), $options));
        $this->set('options',$options);
        if ($this->request->is('post')) {
            $this->User->create();
            $connect = ($this->request->data);
            if ($this->User->save($this->request->data)) {
                $uid = $this->User->id;
                $starid = $connect['User']['star_id'];
                $edge = new UserStarEdge();
                $edge->create();
                $edgedata = array(
                    'UserStarEdge' => array('user_id' => $uid,'star_id' => $starid)
                );
                $edge->set($edgedata);
                $edge->save();
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }
    
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException('No ID supplied');
            $this->redirect(array('controller'=>'users', 'action'=>'index'));
        } 
        $data = $this->User->findById($id); 
        if ($this->request->is('get')) { 
            $this->request->data = $data;
        }
        if ($this->request->is('post') || $this->request->is('put')) {
           $this->User->id = $id; 
           $this->User->save($this->request->data);
           $this->redirect(array('action'=>'index'));
        } 
    }

    public function delete($id = null) {
        $this->request->onlyAllow('post');
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Session->setFlash(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }
}