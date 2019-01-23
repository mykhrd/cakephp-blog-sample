<pre>
    <?php print_r($posts) ?>
</pre>

<h1>Blog Posts</h1>
<p>
    user:<?php echo AuthComponent::user('username') ?>
    <?php echo $this->Html->link(
        'Logout',
        array('controller' => 'users', 'action' => 'logout')
    ); ?>
</p>
<?php echo $this->Flash->render('auth'); ?>
<?php echo $this->Html->link(
    'Add Post',
    array('controller' => 'posts', 'action' => 'add')
); ?>

<table>
    <tr>
        <th>Title</th>
        <th>Actions</th>
        <th>Created</th>
        <th>Author</th>
    </tr>

    <?php
    //start loop
    foreach ($posts as $post): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($post['Post']['title'],
                    array('controller' => 'posts', 'action' => 'view', $post['Post']['id'])); ?>
            </td>
            <td>
                <?php
                echo $this->Html->link(
                    'Edit',
                    array('controller' => 'posts', 'action' => 'edit', $post['Post']['id'])
                );
                ?>
                <?php
                // postLink(); uses JavaScript to do a POST request for deleting the post
                echo $this->Form->postLink(
                    'Delete',
                    array('controller' => 'posts', 'action' => 'delete', $post['Post']['id']),
                    array('confirm' => 'Are you sure?')
                );
                ?>
            </td>
            <td><?php echo $post['Post']['created']; ?></td>
            <td><?php echo $post['User']['username']; ?>
                <?php
                //$UsersController = new UsersController;
                //$Username = $UsersController->getUsernameById($post['Post']['user_id']);
                //echo $Username['User']['username'];
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php unset($post); ?>
</table>