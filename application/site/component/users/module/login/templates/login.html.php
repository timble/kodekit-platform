<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?=helper('behavior.mootools')?>
<?=helper('behavior.validator')?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login" class="-koowa-form">
	<? if($show_title) : ?>
	<h3><?= $module->title ?></h3>
	<? endif ?>

	<fieldset class="input">
	<div class="control-group">
		<label class="control-label" for="modlgn_email"><?= translate('Email') ?>:</label>
		<div class="controls">
			<input id="modlgn_email" class="required validate-email" type="email" name="email" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="modlgn_passwd"><?= translate('Password') ?>:</label>
		<div class="controls">
			<input id="modlgn_passwd" class="required" type="password" name="password" />
            <? if ($user_route = helper('route.user', array('layout' => 'reset', 'access' => 0))): ?>
                <span class="help-block">
			        <small><a href="<?= $user_route ?>"><?= translate('FORGOT_YOUR_PASSWORD'); ?></a></small>
			    </span>
            <? endif; ?>
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= translate('Sign in') ?>" />
        <?= translate('or') ?>
		<a href="<?= helper('route.user', array('layout' => 'register', 'access' => 0)); ?>"><?= translate('Sign up'); ?></a>
	</div>
	</fieldset>
</form>