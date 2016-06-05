<?php
class QuadrantsController extends AppController {
    var $name = 'Quadrants';
    public function index() {
        $this->Quadrant->recursive = 0;
        $quadrants = $this->Quadrant->find('all', array('group' => 'Quadrant.id'));
        $this->set('quadrants', $quadrants);
    }
    public function add() {
    	$this->User = ClassRegistry::init('User');
	    $userid = $this->Auth->user('id');
	    $currentuser = $this->User->find('first', array(
            'conditions' => array('User.id' => $userid)
                ));
    	$role = $currentuser['User']['role'];
    	if (!($role == 'admin')) {
    	    $this->Session->setFlash(__('You do not have the authority to add a quadrant!'));
    	    $this->redirect(array('action'=>'index'));
            }
        $options = array();
        $options = array_filter(array_merge(array(0), $options));
        $this->set('options',$options);
        if ($this->request->is('post')) {
            if ($this->Quadrant->save($this->request->data)) {
                $value = ($this->request->data);
                $name = $value['Quadrant']['name'];
                $description = $value['Quadrant']['description'];
                $this->Session->setFlash(__('The quadrant has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The quadrant could not be saved. Please, try again.')
            );
        }
    }
}