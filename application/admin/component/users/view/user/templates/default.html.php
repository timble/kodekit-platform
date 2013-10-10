<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://js/koowa.js" />
<script src="assets://users/js/users.js" />
<style src="assets://css/koowa.css" />

<?= helper('behavior.validator') ?>

<script type="text/javascript">
    window.addEvent('domready', function() {
        ComUsers.Form.addValidators(['passwordLength','passwordVerify']);
    });
</script>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" id="user-form" class="-koowa-form">
	<input type="hidden" name="enabled" value="<?= $this->getObject('user')->getId() == $user->id ? 1 : 0 ?>" />
	<input type="hidden" name="send_email" value="0" />
	
	<div class="main">
		<div class="title">
			<input class="required" type="text" id="name" name="name" value="<?= $user->name ?>" placeholder="<?= translate('Name') ?>" />
		</div>
		
		<div class="scrollable">
			<fieldset>
				<legend><?= translate('General') ?></legend>
				<div>
				    <label for="email"><?= translate('E-Mail') ?></label>
				    <div>
				        <input class="required validate-email" type="email" id="email" name="email" value="<?= $user->email ?>" />
				    </div>
				</div>
				<div>
				    <label for="params[timezone]"><?= translate('Time Zone') ?></label>
				    <div>
				        <?= helper('com:extensions.listbox.timezones',
				            array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true, 'attribs' => array('class' => 'select-timezone', 'style' => 'width:220px'))) ?>
				    </div>
				</div>
			</fieldset>
			<fieldset>
				<legend><?= translate('Password') ?></legend>
				<div>
				    <label for="password"><?= translate('Password') ?></label>
				    <div>
				        <input class="passwordLength:<?=$params->get('password_length', 6);?>" id="password" type="password" name="password" maxlength="100" />
				        <?=helper('com:users.form.password');?>
				    </div>
				</div>
				<div>
				    <label for="password_verify"><?= translate('Verify Password') ?></label>
				    <div>
				        <input class="passwordVerify matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify" maxlength="100" />
				    </div>
				</div>
			    <? if (!$user->isNew()): ?>
				<div>
				    <div>
				        <label class="checkbox" for="password_change">
				            <input type="checkbox" id="password_change" name="password_change" />
				            <?= translate('Require a change of password in the next sign in') ?>
				        </label>
				    </div>
				</div>
			    <? endif; ?>
			</fieldset>
			<fieldset>
				<legend><?= translate('Language') ?></legend>
				<?= $user->params->render('params') ?>
			</fieldset>
		</div>
	</div>
	
	<div class="sidebar">
        <?= import('default_sidebar.html'); ?>
	</div>
</form>

<script data-inline> $jQuery(".select-timezone").select2(); </script>