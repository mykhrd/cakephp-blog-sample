<h1>Add Category</h1>
<?php
echo $this->Form->create('Category');
echo $this->Form->input('title');
echo $this->Form->end('Save Post');
?>