<h1><?php echo h($user['User']['username']); ?></h1>
<p>
    <small>Created: <?php echo $user['User']['created']; ?></small>
</p>
<p>
    <small>Edited: <?php echo $user['User']['modified']; ?></small>
</p>
<p>
    <small>Role:<?php echo h($user['User']['role']); ?></small>
</p>
