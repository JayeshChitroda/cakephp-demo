<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class PostsController extends AppController {
    public $helpers = array('Html', 'Form');
       
    public function index() {
        $this->set('posts', $this->Post->find('all'));
    }
    
     public function view($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
            return $this->redirect(array('action' => 'index'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
//            throw new NotFoundException(__('Invalid post'));
            $this->Session->setFlash(__('Invalid id.'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->set('post', $post);
    }
    
     public function add() {
        if ($this->request->is('post')) {
//            $this->Post->create();
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to add your post.'));
        }
    }
    
    public function edit($id = null) {
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
            return $this->redirect(array('action' => 'index'));
        }

        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            if ($this->Post->save($this->request->data)) {
                $this->Session->setFlash(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Session->setFlash(__('Unable to update your post.'));
        }

        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }
    
    public function delete($id) {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->Post->delete($id)) {
            $this->Session->setFlash(
                __('The post with id: %s has been deleted.', h($id))
            );
            return $this->redirect(array('action' => 'index'));
        }
    }
    
}

?>
