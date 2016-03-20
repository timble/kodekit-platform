<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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
            <?= $user->role;?>
        </td>
        <td>
            <?= helper('date.humanize', array('date' => '@'.$user->session_time));?>
        </td>
    </tr>
        <?php endforeach; ?>
    </tbody>
</table>