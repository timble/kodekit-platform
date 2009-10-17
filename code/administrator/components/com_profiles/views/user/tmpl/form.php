<? /** $Id: form.php 225 2009-09-21 03:05:13Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<? @style(@$mediaurl.'/com_profiles/css/form.css'); ?>
<? @style(@$mediaurl.'/com_profiles/css/admin.css') ?>

<form action="<?= @route('&id='.@$user->id)?>" method="post" class="adminform" name="adminForm">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('User Details'); ?></legend>
			<label for="name" id="mainlabel"><?= @text('Name'); ?></label>
			<input id="name" type="text" name="name" value="<?= @$user->name ?>" />
			<br />
			<label for="username" id="mainlabel"><?= @text('Username'); ?></label>
			<input id="username" type="text" name="username" value="<?= @$user->username ?>" />
			<br />
			<label for="email" id="mainlabel"><?= @text('E-Mail'); ?></label>
			<input id="email" type="text" name="email" value="<?= @$user->email ?>" />
			<br />
			<label for="password" id="mainlabel"><?= @text('New Password'); ?></label>
			<input id="password" type="text" name="password" value="<?= @$user->password ?>" />
			<br />
			<label for="password2" id="mainlabel"><?= @text('Verify Password'); ?></label>
			<input id="password2" type="text" name="password2" value="<?= 'TODO' ?>" />
			<br />
			<label for="block" id="mainlabel"><?= @text('Block User'); ?></label>
			<?= @helper('select.booleanlist', 'block', null, @$user->block, 'yes', 'no', 'block') ?>
			<br />
			<label for="sendEmail" id="mainlabel"><?= @text('Receive System E-mails'); ?></label>
			<?= @helper('select.booleanlist', 'sendEmail', null, @$user->sendEmail, 'yes', 'no', 'sendEmail') ?>
			<br />
			<label for="registerDate" id="mainlabel"><?= @text('Register Date'); ?></label>
			<?= @helper('date', @$user->registerDate, '%Y-%m-%d %H:%M:%S')?>
			<br />
			<label for="lastvisitDate" id="mainlabel"><?= @text('Last Visit Date'); ?></label>
			<?= @helper('date', @$user->lastvisitDate, '%Y-%m-%d %H:%M:%S')?>
			<br />
		</fieldset>
	</div>
</form>