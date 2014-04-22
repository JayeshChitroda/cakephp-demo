<?php
// app/Controller/UsersController.php
class UsersController extends AppController {

    public function login() {
        
        if ($this->request->is('post')) {         
            $userdata = $this->User->find('all',array('conditions'=>array('username'=>$this->request->data['User']['username'],'password'=>  md5($this->request->data['User']['password']))));
            if (!empty($userdata)) {
                $this->Session->write('User',$this->request->data);
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Invalid username or password, try again'));
        }
    }

    public function logout() {
        $this->Session->delete('User');
        return $this->redirect(array('action' => 'login'));
    }
 
    public function index() {
        $this->checkLogin();
        if ($this->checkLogin()) {
            return $this->redirect(array('action' => 'login'));
        }
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        if ($this->checkLogin()) {
            return $this->redirect(array('action' => 'login'));
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->read(null, $id));
    }

    public function add() {
//        if ($this->checkLogin()) {
//            return $this->redirect(array('action' => 'login'));
//        }
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        if ($this->checkLogin()) {
            return $this->redirect(array('action' => 'login'));
        }
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->read(null, $id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        if ($this->checkLogin()) {
            return $this->redirect(array('action' => 'login'));
        }
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