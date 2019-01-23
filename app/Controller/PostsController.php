<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController
{
    public $helpers = array('Html', 'Form', 'Flash');
    public $components = array('Flash');

    //index
    public function index()
    {
        $uid = $this->Auth->user('id');
        $this->set('posts', $this->Post->find('all', array(
            'fields' => array(
                'id', 'title',
                'created',
                'User.username'),
            'conditions' => array('User.id =' => $uid)
        )));

        if ($uid === '1') {
            $this->set('posts', $this->Post->find('all', array(
                'fields' => array(
                    'id', 'title',
                    'created',
                    'User.username')
            )));
        }

        //debug($uid);
        //exit;
    }

    //view
    public function view($id = null)
    {
        // if the value is not an id, throw an error
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        // $post is post -> $id and if the value is not post show invalid post error
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }
        // set() single post information
        $this->set('post', $post);
    }

    //add
    public function add()
    {
        // if request is post, save the data using the post model with
        if ($this->request->is('post')) {
            $this->Post->create();
            $this->request->data['Post']['user_id'] = $this->Auth->user('id');
            // if data is saved, show success message and return to index
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            // if data is not saved, show error message
            $this->Flash->error(__('Unable to add your post.'));
        }
    }

    //edit
    public function edit($id = null)
    {
        // if $id is not correct, show invalid post error
        if (!$id) {
            throw new NotFoundException(__('Invalid post'));
        }
        // $post is post -> $id and if the value is not post show invalid post error
        $post = $this->Post->findById($id);
        if (!$post) {
            throw new NotFoundException(__('Invalid post'));
        }

        // if the request is post or put, update the post record, else throw an error
        if ($this->request->is(array('post', 'put'))) {
            $this->Post->id = $id;
            //on save show success message and return to index
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            // show error message if record is not updated
            $this->Flash->error(__('Unable to update your post.'));
        }
        // If there is no data set to $this->request->data, set data to previous post record retrieved
        if (!$this->request->data) {
            $this->request->data = $post;
        }
    }

    /** Delete function
     *  This function deletes post specified by $id
     */
    public function delete($id)
    {
        // if the delete request is GET, throw MethodNotAllowedException
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }
        // if deletion is a success show success message, else throw an error
        if ($this->Post->delete($id)) {
            $this->Flash->success(
                __('The post with id: %s has been deleted.', h($id))
            );
        } else {
            $this->Flash->error(
                __('The post with id: %s could not be deleted.', h($id))
            );
        }
        // if all is processed, return to index
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        // registered user can view index
        if (in_array($this->action, array('index', 'view', 'add'))) {
            if (isset($user)) {
                return true;
            }
        }

        // The owner of a post can edit and delete it
        if (in_array($this->action, array('edit', 'delete'))) {
            $postId = (int)$this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }

}
