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

<h1 class="page-header"><?= @('Confirm your Account') ?></h1>

<form action="" method="post" class="josForm form-validate form-horizontal">
    <input type="hidden" name="action" value="confirm" />
    
    <p><?= @text('RESET_PASSWORD_CONFIRM_DESCRIPTION') ?></p>
    
    <div class="control-group">
        <label class="control-label" for="email"><?= @text('Email') ?></label>
        <div class="controls">
            <input id="email" name="email" type="text" class="required" />
            <p class="help-block"><?= @text('RESET_PASSWORD_EMAIL_TIP_TEXT') ?></p>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="token"><?= @text('Token') ?></label>
        <div class="controls">
            <input id="token" name="token" type="text" class="required" />
            <p class="help-block"><?= @text('RESET_PASSWORD_TOKEN_TIP_TEXT') ?></p>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="validate btn"><?= @text('Submit') ?></button>
    </div>
</form>