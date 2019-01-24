<?php
/**
 * Model Category
 */

App::uses('AppModel', 'Model');

class Category extends AppModel
{
    public $validate = array(
        'title' => array(
            'rule' => 'notBlank'
        )
    );

    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id'
        )
    );

    public $hasMany = array(
        'Post' => array(
            'className' => 'Post',
            'foreignKey' => 'category_id',
            //'dependent' => true
        )
    );

    public function isOwnedBy($post, $user)
    {
        return $this->field('id', array('id' => $post, 'user_id' => $user)) !== false;
    }

}

