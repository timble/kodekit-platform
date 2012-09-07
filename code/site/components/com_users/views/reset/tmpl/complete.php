<?
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?=@helper('behavior.mootools');?>

<h1 class="page-header"><?= @text('Reset your Password') ?></h1>

<form action="" method="post" class="josForm form-validate form-horizontal">
    <input type="hidden" name="action" value="complete" />

    <input type="hidden" name="id" value="<?= KRequest::get('get.id', 'int') ?>" />
    <input type="hidden" name="token" value="<?= KRequest::get('get.token', 'alnum') ?>" />
    
    <p><?= @text('RESET_PASSWORD_COMPLETE_DESCRIPTION') ?></p>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input id="password" name="password" type="password" class="required validate-password" />
            <p class="help-block"><?= @text('RESET_PASSWORD_PASSWORD1_TIP_TEXT') ?></p>
            <?=@helper('com://admin/users.template.helper.form.password', array('length' => $parameters->get('password_length')));?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password_verify"><?= @text('Verify Password') ?></label>
        <div class="controls">
            <input id="password_verify" name="password_verify" type="password" class="required validate-password" />
            <p class="help-block"><?= @text('RESET_PASSWORD_PASSWORD2_TIP_TEXT') ?></p>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="validate btn"><?= @text('Submit') ?></button>
    </div>
</form>