<!--<pre>-->
<!--    --><?php
//    print_r($categories);
//    ?>
<!--</pre>-->
<h1>Blog Categories</h1>
<?php echo $this->Html->link(
    'Add Category',
    array('controller' => 'categories', 'action' => 'add')
); ?>

<table>
    <tr>
        <th>Title</th>
        <th>Action</th>
        <th>Created</th>
        <th>Author</th>
    </tr>

    <?php
    foreach ($categories as $category): ?>
        <tr>
            <td><?php echo $category['Category']['title']; ?></td>
            <td>

                <?php
                $user = AuthComponent::user('username');

                if (AuthComponent::user('role') === 'admin' || $category['User']['username'] === $user) {
                    echo $this->Html->link(
                        'Edit',
                        array('controller' => 'categories', 'action' => 'edit', $category['Category']['id']),
                        array('class' => 'edit')
                    );


                    echo $this->Form->postLink(
                        'Delete',
                        array('controller' => 'categories', 'action' => 'delete', $category['Category']['id']),
                        array('confirm' => 'Are you sure?'),
                        array('class' => 'delete')
                    );
                }


                ?>

            </td>
            <td><?php echo $category['Category']['created']; ?></td>
            <td><?php echo $category['User']['username']; ?></td>
        </tr>
    <?php endforeach; ?>
    <?php unset($post); ?>
</table>