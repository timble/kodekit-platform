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

<script>
    Window.onDomReady(function(){
        document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); } );
    });
</script>

<form action="" method="post" name="userform" autocomplete="off" class="form-validate form-horizontal">
    <input type="hidden" name="action" value="save" />

    <div class="page-header">
        <h1><?= @escape($parameters->get('page_title')) ?></h1>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="username"><?= @text('Username') ?></label>
        <div class="controls">
            <span class="uneditable-input"><?= $user->username ?></span>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Your Name') ?></label>
        <div class="controls">
            <input class="inputbox required" type="text" id="name" name="name" value="<?= @escape($user->name) ?>" size="40" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Email') ?></label>
        <div class="controls">
            <input class="inputbox required validate-email" type="text" id="email" name="email" value="<?= @escape($user->email) ?>" size="40" />
        </div>
    </div>
    
    <? if($user->password) : ?>
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input class="inputbox validate-password" type="password" id="password" name="password" value="" size="40" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Verify Password') ?></label>
        <div class="controls">
            <input class="inputbox validate-passverify" type="password" id="password_verify" name="password_verify" size="40" />
        </div>
    </div>
    <? endif ?>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Timezone') ?></label>
        <div class="controls">
            <?= @helper('com://admin/settings.template.helper.listbox.timezones',
                array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true)) ?>
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Username') ?></label>
        <div class="controls">
            <input type="password" id="password" name="password" class="inputbox" size="18" alt="password" />
        </div>
    </div>
    

    <?= $user->params->render() ?>
    
    <div class="form-actions">
        <button class="btn validate" type="submit" onclick="submitbutton( this.form );return false;"><?= @text('Save') ?></button>
    </div>
</form>