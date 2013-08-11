<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="media://js/koowa.js" />
<script src="media://users/js/users.js" />
<style src="media://css/koowa.css" />

<?= @helper('behavior.validator') ?>

<script type="text/javascript">
    window.addEvent('domready', function() {
        ComUsers.Form.addValidators(['passwordLength','passwordVerify']);
    });
</script>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="" method="post" id="user-form" class="-koowa-form">
	<input type="hidden" name="enabled" value="<?= $this->getObject('user')->getId() == $user->id ? 1 : 0 ?>" />
	<input type="hidden" name="send_email" value="0" />
	
	<div class="main">
		<div class="title">
			<input class="required" type="text" id="name" name="name" value="<?= $user->name ?>" placeholder="<?= @text('Name') ?>" />
		</div>
		
		<div class="scrollable">
			<fieldset>
				<legend><?= @text('General') ?></legend>
				<div>
				    <label for="email"><?= @text('E-Mail') ?></label>
				    <div>
				        <input class="required validate-email" type="email" id="email" name="email" value="<?= $user->email ?>" />
				    </div>
				</div>
				<div>
				    <label for="params[timezone]"><?= @text('Time Zone') ?></label>
				    <div>
				        <?= @helper('com:extensions.listbox.timezones',
				            array('name' => 'params[timezone]', 'selected' => $user->params->get('timezone'), 'deselect' => true, 'attribs' => array('class' => 'select-timezone', 'style' => 'width:220px'))) ?>
				    </div>
				</div>
			</fieldset>
			<fieldset>
				<legend><?= @text('Password') ?></legend>
				<div>
				    <label for="password"><?= @text('Password') ?></label>
				    <div>
				        <input class="passwordLength:<?=$params->get('password_length', 6);?>" id="password" type="password" name="password" maxlength="100" />
				        <?=@helper('com:users.form.password');?>
				    </div>
				</div>
				<div>
				    <label for="password_verify"><?= @text('Verify Password') ?></label>
				    <div>
				        <input class="passwordVerify matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify" maxlength="100" />
				    </div>
				</div>
			    <? if (!$user->isNew()): ?>
				<div>
				    <div>
				        <label class="checkbox" for="password_change">
				            <input type="checkbox" id="password_change" name="password_change" />
				            <?= @text('Require a change of password in the next sign in') ?>
				        </label>
				    </div>
				</div>
			    <? endif; ?>
			</fieldset>
			<fieldset>
				<legend><?= @text('Language') ?></legend>
				<?= $user->params->render('params') ?>
			</fieldset>
		</div>
	</div>
	
	<div class="sidebar">
        <?= @template('default_sidebar.html'); ?>
	</div>
</form>

<script data-inline> $jQuery(".select-timezone").select2(); </script>