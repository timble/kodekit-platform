<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<table class="table">
    <thead>
    <tr>
        <th>
            <strong><?= translate( 'Name' ); ?></strong>
        </th>
        <th>
            <strong><?= translate( 'User Group' ); ?></strong>
        </th>
        <th>
            <strong><?= translate( 'Last Activity' ); ?></strong>
        </th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user) : ?>
    <tr>
        <td>
            <a href="<?=  route('component=users&view=user&id='. $user->id); ?>" title="<?= translate( 'Edit User' ) ?>">
                <?= $user->name; ?>
            </a>
        </td>
        <td>
            <?= $user->role_name;?>
        </td>
        <td>
            <?= helper('date.humanize', array('date' => '@'.$user->session_time));?>
        </td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>