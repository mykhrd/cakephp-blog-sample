<h1>Blog posts</h1>
<?php echo $this->Html->link(
    'Add Post',
    array('controller' => 'posts', 'action' => 'add')
); ?>

<table>
    <tr>
        <th>Id</th>
        <th>Title</th>
        <th>Actions</th>
        <th>Created</th>
    </tr>

    <?php
    //start loop
    foreach ($posts as $post): ?>
        <tr>
            <td><?php echo $post['Post']['id']; ?></td>
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
        </tr>
    <?php endforeach; ?>
    <?php unset($post); ?>
</table>