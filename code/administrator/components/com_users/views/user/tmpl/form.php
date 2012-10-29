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

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://com_users/js/user.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @helper('behavior.validator') ?>

<script type="text/javascript">
    window.addEvent('domready', function() {
        ComUsers.Form.addValidator('passwordLength');
    });
</script>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="user-form" class="-koowa-form">
	<input type="hidden" name="enabled" value="0" />
	<input type="hidden" name="send_email" value="0" />
	
	<div class="form-content row-fluid">
		<div class="span8">
			<fieldset class="form-horizontal">
				<legend><?= @text('General') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="name"><?= @text('Name') ?></label>
				    <div class="controls">
				        <input class="required" type="text" id="name" name="name" value="<?= $user->name ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="email"><?= @text('E-Mail') ?></label>
				    <div class="controls">
				        <input class="required validate-email" type="text" id="email" name="email" value="<?= $user->email ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="params[timezone]"><?= @text('Time Zone') ?></label>
				    <div class="controls">
				        <?= @helper('com://admin/extensions.template.helper.listbox.timezones',
				            array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true, 'attribs' => array('class' => 'chzn-select'))) ?>
				    </div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<legend><?= @text('Password') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="password"><?= @text('Password') ?></label>
				    <div class="controls">
				        <input class="passwordLength:<?=$params->get('password_length', 6);?>" id="password" type="password" name="password" maxlength="100" />
				        <?=@helper('com://admin/users.template.helper.form.password');?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="password_verify"><?= @text('Verify Password') ?></label>
				    <div class="controls">
				        <input class="validate-match matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify" maxlength="100" />
				    </div>
				</div>
                <? if (!$user->isNew()): ?>
				<div class="control-group">
				    <div class="controls">
				        <label class="checkbox" for="password_change">
				            <input type="checkbox" id="password_change" name="password_change" />
				            <?= @text('Require a change of password in the next sign in') ?>
				        </label>
				    </div>
				</div>
                <? endif; ?>
			</fieldset>
			<fieldset class="form-horizontal">
				<legend><?= @text('Language') ?></legend>
				<?= $user->params->render('params') ?>
			</fieldset>
		</div>
		<div class="span4">
			<fieldset class="form-horizontal">
				<legend><?= @text('System Information') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="enabled"><?= @text('Enable User') ?></label>
				    <div class="controls">
				        <input type="checkbox" id="enabled" name="enabled" value="1" <?= $user->enabled ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="send_email"><?= @text('Receive System E-mails') ?></label>
				    <div class="controls">
				        <input type="checkbox" id="send_email" name="send_email" value="1" <?= $user->send_email ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
				<? if (!$user->isNew()): ?>
				<div class="control-group">
				    <label class="control-label"><?= @text('Register Date') ?></label>
				    <div class="controls">
				        <?= @helper('date.format', array('date' => $user->created_on, 'format' => 'Y-m-d H:i:s')) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label"><?= @text('Last signed in') ?></label>
				    <div class="controls">
				        <? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
				        	<?= @text('Never') ?>
				        <? else : ?>
				        	<?= @helper('date.format', array('date' => $user->last_visited_on, 'format' => 'Y-m-d H:i:s')) ?>
				        <? endif ?>
				    </div>
				</div>
				<? endif; ?>
			</fieldset>
			<fieldset>
				<legend><?= @text('Role') ?></legend>
				<div class="control-group">
				    <div class="controls">
				        <?= @helper('listbox.radiolist', array(
				        		'list'     => @service('com://admin/users.model.roles')->sort('id')->getList(),
				        		'selected' => $user->users_role_id,
				        		'name'     => 'users_role_id',
				                'text'     => 'name',
				        	));
				        ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
</form>