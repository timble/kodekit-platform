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

<script src="media://lib_koowa/js/koowa.js" />

<script type="text/javascript">
    Window.onDomReady(function(){
        //document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); } );
    });
</script>

<? if(isset($this->message)) : ?>
        $this->display('message');
<? endif; ?>

<form action="" method="post" id="josForm" name="josForm" class="form-validate form-horizontal">
    <input type="hidden" name="action" value="save" />

    <div class="page-header">
        <h1><?= @escape($parameters->get('page_title')) ?></h1>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="name">
            <?= @text('Name') ?>:
        </label>
        <div class="controls">
            <input type="text" name="name" value="<?= @escape($user->name) ?>" class="required" maxlength="50" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">
            <?= @text('Email') ?>:
        </label>
        <div class="controls">
            <input type="text" name="email" value="<?= @escape($user->email) ?>" class="required validate-email" maxlength="100" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="">
            <?= @text('Password') ?>:
        </label>
        <div class="controls">
            <input class="inputbox required validate-password" type="password" name="password" value="" />
            <?=@helper('com://admin/users.template.helper.form.password', array('length' => $parameters->get('password_length')));?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="">
            <?= @text('Verify Password') ?>:
        </label>
        <div class="controls">
            <input class="required validate-passverify" type="password" id="password2" name="password_verify"  value="" />
        </div>
    </div>
    
    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= @text('Register') ?></button>
    </div>
</form>