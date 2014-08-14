<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<fieldset>
    <legend><?= translate('System Information') ?></legend>
    <div>
        <label for="enabled"><?= translate('Enable User') ?></label>
        <div>
            <input <?= object('user')->getId() == $user->id ? 'disabled="disabled"' : ''?> type="checkbox" id="enabled" name="enabled" value="1" <?= $user->enabled ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <? if (!$user->isNew()): ?>
        <div>
            <label><?= translate('Register Date') ?></label>
            <div>
                <?= helper('date.format', array('date' => $user->created_on, 'format' => 'Y-m-d H:i:s')) ?>
            </div>
        </div>
        <div>
            <label><?= translate('Last signed in') ?></label>
            <div>
                <? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
                    <?= translate('Never') ?>
                <? else : ?>
                    <?= helper('date.format', array('date' => $user->last_visited_on, 'format' => 'Y-m-d H:i:s')) ?>
                <? endif ?>
            </div>
        </div>
    <? endif; ?>
</fieldset>
<? if(@object('user')->getRole() > $user->role_id) : ?>
<fieldset>
    <legend><?= translate('Role') ?></legend>
    <div>
        <div><?= helper('select.groups', array('selected' => $user->role_id, 'name' => 'role_id')); ?></div>
    </div>
</fieldset>
<? endif ?>