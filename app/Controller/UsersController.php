<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController
{

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->Auth->allow('login', 'logout');
    }

    public function index()
    {
        debug($this->User->createPassword('admin'));
        $this->User->recursive = -1;
        $this->set('users', $this->paginate());
    }

    public function login()
    {
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function view($id = null)
    {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    public function add()
    {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            // Exception
            throw new BadRequestException(__('Invalid Request'));
        }
    }

    public function edit($id = null)
    {
        $this->User->id = $id;
        if (empty($id) || !$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null)
    {
        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (empty($id) || !$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));

        } else {
            $this->Flash->error(__('User was not deleted'));
        }

        return $this->redirect(array('action' => 'index'));
    }

    public function isAuthorized($user)
    {
        if ($user['role'] === 'admin') {
            return true;
        } else {
            $this->Flash->error(__('Error'));
            $this->redirect(array('controller' => 'posts', 'action' => 'index'));
        }
        return parent::isAuthorized($user);
    }
}