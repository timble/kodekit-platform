<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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
    <div>
        <label for="send_email"><?= translate('Receive System E-mails') ?></label>
        <div>
            <input type="checkbox" id="send_email" name="send_email" value="1" <?= $user->send_email ? 'checked="checked"' : '' ?> />
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
<fieldset>
    <legend><?= translate('Role') ?></legend>
    <div>
        <div><?= helper('select.groups', array('selected' => $user->role_id)); ?></div>
    </div>
</fieldset>