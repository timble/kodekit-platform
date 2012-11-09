<?php
/**
 * @version     $Id: default.php 4805 2012-08-23 22:31:13Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<table class="table">
    <thead>
    <tr>
        <th>
            <strong><?= @text( 'Name' ); ?></strong>
        </th>
        <th>
            <strong><?= @text( 'User Group' ); ?></strong>
        </th>
        <th>
            <strong><?= @text( 'Application' ); ?></strong>
        </th>
        <th>
            <strong><?= @text( 'Last Activity' ); ?></strong>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) : ?>
    <tr>
        <td>
            <a href="<?=  @route('option=com_users&view=user&id='. $user->id); ?>" title="<?= @text( 'Edit User' ) ?>">
                <?= $user->name; ?>
            </a>
        </td>
        <td>
            <?= $user->role_name;?>
        </td>
        <td>
            <?= $user->loggedin_application; ?>
        </td>
        <td>
            <?= @helper('date.humanize', array('date' => '@'.$user->loggedin_on));?>
        </td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>