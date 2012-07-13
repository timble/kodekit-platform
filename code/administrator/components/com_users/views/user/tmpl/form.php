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

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @helper('behavior.validator') ?>
<? /* @TODO move this into a separate JS file */ ?>
<script>
if(Form && Form.Validator) {
    Form.Validator.add('validate-match', {
		errorMsg: function(element, props){
			return Form.Validator.getMsg('match').substitute({matchName: props.matchName || document.id(props.matchInput).get('name')});
		},
		test: function(element, props){
			var eleVal = element.get('value');
			var matchVal = document.id(props.matchInput) && document.id(props.matchInput).get('value');
			return matchVal ? eleVal == matchVal : true;
		}
	});
}
</script>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="user-form" class="-koowa-form">
	<div class="form-content">
		<div class="span8">
			<fieldset class="form-horizontal">
				<legend><?= @text('User Details') ?></legend>
				<div class="control-group">
				    <label class="control-label" for="name"><?= @text('Name') ?></label>
				    <div class="controls">
				        <input class="required" type="text" name="name" value="<?= $user->name ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="email"><?= @text('E-Mail') ?></label>
				    <div class="controls">
				        <input class="required validate-email" type="text" name="email" value="<?= $user->email ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="password"><?= @text('New Password') ?></label>
				    <div class="controls">
				        <input id="password" type="password" name="password" maxlength="100" />
                        <?=@helper('com://admin/users.template.helper.form.passwcheck', array('min_len' => $params->get('min_passw_len')));?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="password_verify"><?= @text('Verify Password') ?></label>
				    <div class="controls">
				        <input class="validate-match matchInput:'password' matchName:'password'" type="password" name="password_verify" maxlength="100" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="params[timezone]"><?= @text('Time Zone') ?></label>
				    <div class="controls">
				        <?= @helper('com://admin/settings.template.helper.listbox.timezones',
				            array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true)) ?>
				    </div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<legend><?= @text('User Parameters') ?></legend>
				<?= $user->params->render('params') ?>
			</fieldset>
		</div>
		<div class="span4">
			<fieldset class="form-horizontal">
				<legend><?= @text('System Information') ?></legend>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Enable User') ?></label>
				    <div class="controls">
				        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $user->enabled)) ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Receive System E-mails') ?></label>
				    <div class="controls">
				        <?= @helper('select.booleanlist', array('name' => 'send_email', 'selected' => $user->send_email)) ?>
				    </div>
				</div>
				<? if (!$user->isNew()): ?>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Register Date') ?></label>
				    <div class="controls">
				        <? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
				        	<?= @text('Never') ?>
				        <? else : ?>
				        	<?= @helper('date.format', array('date' => $user->registered_on, 'format' => 'Y-m-d H:i:s')) ?>
				        <? endif ?>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text('Last Visit') ?></label>
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
				<legend><?= @text('Group') ?></legend>
				<div class="control-group">
				    <div class="controls">
				        <?= @helper('com://admin/groups.template.helper.select.groups', array('selected' => $user->id ? $user->users_group_id : 18, 'name' => 'users_group_id', 'attribs' => array('class' => 'required'))) ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
</form>