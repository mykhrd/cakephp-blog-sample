<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = __d('cake_dev', 'CakePHP: the rapid development php framework');
$cakeVersion = __d('cake_dev', 'CakePHP %s', Configure::version())
?>
<!DOCTYPE html>
<html>
<head>
    <?php echo $this->Html->charset(); ?>
    <title>
        <?php echo $cakeDescription ?>:
        <?php echo $this->fetch('title'); ?>
    </title>
    <?php
    echo $this->Html->meta('icon');
    echo $this->Html->css('cake.generic');
    echo $this->fetch('meta');
    echo $this->fetch('css');
    echo $this->fetch('script');
    ?>
</head>
<body>
<div id="container">
    <div id="header">
        <p><?php if (AuthComponent::user('id')) { ?>
                user: <?php echo AuthComponent::user('username') ?>
            <?php } ?>

            <?php
            if (AuthComponent::user('id')) {
                echo $this->Html->link(
                    'Logout',
                    array('controller' => 'users', 'action' => 'logout')
                );
            }
            ?>
        </p>
        <p>
            <?php
            if (AuthComponent::user('id')) {
                echo $this->Html->link(
                    'Manage Posts',
                    array('controller' => 'posts', 'action' => 'index')
                );
            }
            ?>

            <?php
            if (AuthComponent::user('role') === 'admin') {
                echo $this->Html->link(
                    'Manage Users',
                    array('controller' => 'users', 'action' => 'index')
                );
            }
            ?>

            <?php
            if (AuthComponent::user('id')) {
                echo $this->Html->link(
                    'Manage Categories',
                    array('controller' => 'categories', 'action' => 'index')
                );
            }
            ?>
        </p>
    </div>
    <div id="content">

        <?php echo $this->Flash->render(); ?>

        <?php echo $this->fetch('content'); ?>
    </div>
    <div id="footer">
        <?php echo $this->Html->link(
            $this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
            'https://cakephp.org/',
            array('target' => '_blank', 'escape' => false, 'id' => 'cake-powered')
        );
        ?>
        <p>
            <?php echo $cakeVersion; ?>
        </p>
    </div>
</div>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>
