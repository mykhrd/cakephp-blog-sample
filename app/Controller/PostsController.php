<?php
App::uses('AppController', 'Controller');

class PostsController extends AppController
{
    public $uses = array('Post', 'Category');

    //index
    public function index()
    {

        $role = $this->Auth->user('role');

        //$is_deleted = array('is_deleted =' => 0);
        $conditions = array(
            'User.role =' => $role,
            'is_deleted =' => 0
        );

        if ($role === 'admin') {
            $conditions = array(
                'is_deleted =' => 0
            );
        }

        $this->Paginator->settings = array(
            'conditions' => $conditions,
            'limit' => 5,
            'fields' => array(
                'id',
                'title',
                'created',
                'category_id',
                'User.username',
                'Category.id',
                'Category.title'
            )
        );
        $posts = $this->Paginator->paginate('Post');
        $this->set('posts', $posts);
    }

    //view
    public function view($id = null)
    {
        // if the value is not an id, throw an error
        if (empty($id)) {
            throw new NotFoundException(__('Invalid post'));
        }
        // $post is post -> $id and if the value is not post show invalid post error
        $post = $this->Post->findById($id);
        if (empty($post)) {
            throw new NotFoundException(__('Invalid post'));
        }
        // set() single post information
        $this->set('post', $post);
    }

    //add
    public function add()
    {
        $categories = $this->Category->find('list', array(
            'fields' => array(
                'id',
                'title',
            ),
        ));
        $this->set('categories', $categories);

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
        $categories = $this->Category->find('list', array(
            'fields' => array(
                'id',
                'title',
            ),
        ));
        $this->set('categories', $categories);

        // if $id is not correct, show invalid post error
        if (empty($id)) {
            throw new NotFoundException(__('Invalid post'));
        }

        // $post is post -> $id and if the value is not post show invalid post error
        $post = $this->Post->findById($id);
        if (empty($post)) {
            throw new NotFoundException(__('Invalid post'));
        }

        // if the request is post or put, update the post record, else throw an error
        if ($this->request->is(array('post', 'put'))) {
            //$this->Post->id = $id;
            //on save show success message and return to index
            if ($this->Post->save($this->request->data)) {
                $this->Flash->success(__('Your post has been updated.'));
                return $this->redirect(array('action' => 'index'));
            }
            // show error message if record is not updated
            $this->Flash->error(__('Unable to update your post.'));
        }
        // If there is no data set to $this->request->data, set data to previous post record retrieved

        if (empty($this->request->data)) {
            $this->request->data = $post;
        }
    }

    /** Delete function
     *  This function deletes post specified by $id
     */
    public function delete($id = null)
    {
        // if the delete request is GET, throw MethodNotAllowedException
        if ($this->request->is('get')) {
            throw new MethodNotAllowedException();
        }

        if ($this->request->is(array('post', 'put'))) {

            //update is_deleted field value to true
            $data = array('id' => $id, 'is_deleted' => 1);
            if ($this->Post->save($data)) {
                $this->Flash->success(
                    __('The post with id: %s has been deleted.', h($id))
                );
            } else {
                $this->Flash->error(
                    __('The post with id: %s could not be deleted.', h($id))
                );
            }
        }

        // if all is processed, return to index
        return $this->redirect(array('action' => 'index'));

        // if deletion is a success show success message, else throw an error
//        if ($this->Post->delete($id)) {
//            $this->Flash->success(
//                __('The post with id: %s has been deleted.', h($id))
//            );
//        } else {
//            $this->Flash->error(
//                __('The post with id: %s could not be deleted.', h($id))
//            );
//        }

    }

    public function isAuthorized($user = null)
    {
        // registered user can view index
        if (in_array($this->action, array('index', 'add'))) {
            if (isset($user)) {
                return true;
            }
        }

        // The owner of a post can edit, view and delete it
        if (in_array($this->action, array('edit', 'delete', 'view'))) {

            $postId = (int)$this->request->params['pass'][0];
            if ($this->Post->isOwnedBy($postId, $user['id']) || $user['role'] === 'admin') {
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
