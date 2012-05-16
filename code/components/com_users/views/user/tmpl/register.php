<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?=@helper('behavior.mootools');?>

<style src="media://com_users/css/site.css" />
<script src="media://lib_koowa/js/koowa.js" />

<script type="text/javascript">
    Window.onDomReady(function(){
        //document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); } );
        $('rpoohcheck').set('value','');
    });
</script>

<? if(isset($this->message)) : ?>
        $this->display('message');
<? endif; ?>

<form action="" method="post" id="josForm" name="josForm" class="form-validate">
    <input type="hidden" name="action" value="save" />

    <? if($parameters->def('show_page_title', 1)) : ?>
        <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>"><?= @escape($parameters->get('page_title')) ?></div>
    <? endif ?>

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
        <tr>
            <td width="30%" height="40">
                <label id="namemsg" for="name">
                    <?= @text('Name') ?>:
                </label>
            </td>
            <td>
                <input type="text" name="name" id="name" size="40" value="<?= @escape($user->name) ?>" class="inputbox required" maxlength="50" /> *
            </td>
        </tr>
        <tr>
            <td height="40">
                <label id="usernamemsg" for="username">
                    <?= @text('User name') ?>:
                </label>
            </td>
            <td>
                <input type="text" id="username" name="username" size="40" value="<?= @escape($user->username) ?>" class="inputbox required validate-username" maxlength="25" /> *
            </td>
        </tr>
        <tr>
            <td height="40">
                <label id="emailmsg" for="email">
                    <?= @text('Email') ?>:
                </label>
            </td>
            <td>
                <input type="text" id="email" name="email" size="40" value="<?= @escape($user->email) ?>" class="inputbox required validate-email" maxlength="100" /> *
            </td>
        </tr>
        <tr>
            <td height="40">
                <label id="pwmsg" for="password">
                    <?= @text('Password') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox required validate-password" type="password" id="password" name="password" size="40" value="" /> *
                <?=@helper('com://admin/users.template.helper.form.passwcheck', array('min_len' => $parameters->get('min_passw_len')));?>
            </td>
        </tr>
        <tr>
            <td height="40">
                <label id="pw2msg" for="password2">
                    <?= @text('Verify Password') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox required validate-passverify" type="password" id="password_verify" name="password_verify" size="40" value="" /> *
            </td>
        </tr>
        <tr class="pooh">
            <td height="40">
                <label id="poohcheckmsg" for="poohcheck">
                    <?= @text('pooh') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox" type="text" id="poohcheck" name="poohcheck" size="40" value="" />
            </td>
        </tr>
        <tr class="pooh">
        	<td height="40">
                <label id="rpoohcheckmsg" for="rpoohcheck">
                    <?= @text('rpooh') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox" type="text" id="rpoohcheck" name="rpoohcheck" size="40" value="filled" />
            </td>
        </tr>
        <tr>
            <td colspan="2" height="40">
                <?= @text('REGISTER_REQUIRED') ?>
            </td>
        </tr>
    </table>
    <?=@helper('com://site/users.template.helper.spam.timestamp');?>
    <button class="button validate" type="submit"><?= @text('Register') ?></button>
</form>