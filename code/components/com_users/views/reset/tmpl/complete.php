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

<div class="componentheading">
    <?= @text('Reset your Password') ?>
</div>

<form action="<?= @route() ?>" method="post" class="josForm form-validate">
    <input type="hidden" name="action" value="complete" />

    <input type="hidden" name="id" value="<?= KRequest::get('get.id', 'int') ?>" />
    <input type="hidden" name="token" value="<?= KRequest::get('get.token', 'alnum') ?>" />

    <table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
        <tr>
            <td colspan="2" height="40">
                <p><?= @text('RESET_PASSWORD_COMPLETE_DESCRIPTION') ?></p>
            </td>
        </tr>
        <tr>
            <td height="40">
                <label for="password" class="hasTip" title="<?= @text('RESET_PASSWORD_PASSWORD1_TIP_TITLE') ?>::<?= @text('RESET_PASSWORD_PASSWORD1_TIP_TEXT') ?>"><?= @text('Password') ?>:</label>
            </td>
            <td>
                <input id="password" name="password" type="password" class="required validate-password" />
            </td>
        </tr>
        <tr>
            <td height="40">
                <label for="password_verify" class="hasTip" title="<?= @text('RESET_PASSWORD_PASSWORD2_TIP_TITLE') ?>::<?= @text('RESET_PASSWORD_PASSWORD2_TIP_TEXT') ?>"><?= @text('Verify Password') ?>:</label>
            </td>
            <td>
                <input id="password_verify" name="password_verify" type="password" class="required validate-password" />
            </td>
        </tr>
    </table>

    <button type="submit" class="validate"><?= @text('Submit') ?></button>
</form>