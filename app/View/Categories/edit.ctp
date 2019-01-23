<h1>Edit Category</h1>
<?php
echo $this->Form->create('Category');
echo $this->Form->input('title');
echo $this->Form->input('id', array('type' => 'hidden'));
echo $this->Form->end('Save Post');
?>