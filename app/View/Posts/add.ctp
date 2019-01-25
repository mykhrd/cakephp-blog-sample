<h1>Add Post</h1>
<?php
echo $this->Form->create('Post');
echo $this->Form->input('title');
echo $this->Form->input('body', array('rows' => '3'));
echo $this->Form->input('category_id', array(
    'options' => array($categories),
    'empty' => ''
));
//echo $this->Form->input('is_deleted', array('type' => 'hidden', 'value' => false));
echo $this->Form->end('Save Post');
?>