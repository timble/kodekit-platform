<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script>
    Window.onDomReady(function(){
        document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); } );
    });
</script>

<form action="" method="post" name="userform" autocomplete="off" class="form-validate">
    <input type="hidden" name="action" value="save" />

    <? if($parameters->def('show_page_title', 1)) : ?>
        <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
            <?= @escape($parameters->get('page_title')) ?>
        </div>
    <? endif ?>
    <table cellpadding="5" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <label for="username">
                <?= @text('User Name') ?>:
            </label>
        </td>
        <td>
            <span><?= $user->username ?></span>
        </td>
    </tr>
    <tr>
        <td width="120">
            <label for="name">
                <?= @text('Your Name') ?>:
            </label>
        </td>
        <td>
            <input class="inputbox required" type="text" id="name" name="name" value="<?= @escape($user->name) ?>" size="40" />
        </td>
    </tr>
    <tr>
        <td>
            <label for="email">
                <?= @text('email') ?>:
            </label>
        </td>
        <td>
            <input class="inputbox required validate-email" type="text" id="email" name="email" value="<?= @escape($user->email) ?>" size="40" />
        </td>
    </tr>
    <? if($user->password) : ?>
        <tr>
            <td>
                <label for="password">
                    <?= @text('Password') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox validate-password" type="password" id="password" name="password" value="" size="40" />
            </td>
        </tr>
        <tr>
            <td>
                <label for="password2">
                    <?= @text('Verify Password') ?>:
                </label>
            </td>
            <td>
                <input class="inputbox validate-passverify" type="password" id="password_verify" name="password_verify" size="40" />
            </td>
        </tr>
    <? endif ?>
    </table>
    <?= $user->params->render(); ?>

    <button class="button validate" type="submit" onclick="submitbutton( this.form );return false;"><?= @text('Save') ?></button>
</form>