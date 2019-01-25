<?php
App::uses('AppController', 'Controller');

class CategoriesController extends AppController
{
    public $uses = array('Category', 'Post');
    public $helpers = array('Html', 'Form', 'Flash');
    public $components = array('Flash');


    public function index()
    {
        $categories = $this->Category->find('all', array(
            'fields' => array(
                'id',
                'title',
                'created',
                'User.username'
            ),
        ));
        $this->set('categories', $categories);
    }

    //view
    public function view()
    {
        $this->redirect(array('action' => 'index'));
    }

    //add
    public function add()
    {

        if ($this->request->is('post')) {
            $this->Category->create();
            $this->request->data['Category']['user_id'] = $this->Auth->user('id');
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('Your category has been saved.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to add your category.'));
        }
    }

    //edit
    public function edit($id = null)
    {
        if (!$id) {
            throw new NotFoundException(__('Invalid category'));
        }
        $category = $this->Category->findById($id);
        if (!$category) {
            throw new NotFoundException(__('Invalid category'));
        }
        if ($this->request->is(array('post', 'put'))) {
            $this->Category->id = $id;
            if ($this->Category->save($this->request->data)) {
                $this->Flash->success(__('Your category has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(__('Unable to update your category.'));
        }
        if (!$this->request->data) {
            $this->request->data = $category;
        }
    }

    //delete
    public function delete($id)
    {
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }


        if ($this->Category->delete($id)) {

            $conditions = array('Post.category_id' => $id);
            $this->Post->updateAll(array(
                $conditions,
                'Post.category_id' => null
            ));

            $this->Flash->success(
                __('The category with id: %s has been deleted.', h($id))
            );


        } else {
            $this->Flash->error(
                __('The category with id: %s could not be deleted.', h($id))
            );
        }
        // if all is processed, return to index
        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        // registered user can view index
        if (in_array($this->action, array('index', 'add'))) {
            if (isset($user)) {
                return true;
            }
        }

        // The owner of a post can edit, view and delete it
        if (in_array($this->action, array('edit', 'delete', 'view'))) {

            $categoryId = (int)$this->request->params['pass'][0];
            if ($this->Category->isOwnedBy($categoryId, $user['id']) || $user['role'] === 'admin') {
                return true;
            } else {
                $this->Flash->error(
                    __('Error')
                );
                return $this->redirect(array('action' => 'index'));
            }
        }
        return parent::isAuthorized($user);
    }
}
