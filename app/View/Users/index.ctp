<h1>Blog Users</h1>
<?php echo $this->Html->link(
    'Add User',
    array('controller' => 'users', 'action' => 'add')
); ?>

<table>
    <tr>
        <th>Username</th>
        <th>Role</th>
        <th>Action</th>
        <th>Created</th>
    </tr>

    <?php
    //start loop
    foreach ($users as $user): ?>
        <tr>
            <td>
                <?php echo $this->Html->link($user['User']['username'],
                    array('controller' => 'users', 'action' => 'view', $user['User']['id'])); ?>
            </td>
            <td><?php echo $user['User']['role']; ?></td>
            <td>
                <?php
                echo $this->Html->link(
                    'Edit',
                    array('controller' => 'users', 'action' => 'edit', $user['User']['id'])
                );
                ?>
                <?php
                // postLink(); uses JavaScript to do a POST request for deleting the post
                echo $this->Form->postLink(
                    'Delete',
                    array('controller' => 'users', 'action' => 'delete', $user['User']['id']),
                    array('confirm' => 'Are you sure?')
                );
                ?>
            </td>
            <td><?php echo $user['User']['created']; ?></td>
        </tr>
    <?php endforeach; ?>
</table>