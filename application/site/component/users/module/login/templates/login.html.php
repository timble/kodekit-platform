<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.mootools')?>
<?= helper('behavior.validator')?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login" class="-koowa-form">
    <? if($module->getParameters()->get('show_title', false)) : ?>
        <h3><?= $module->title ?></h3>
    <? endif ?>

	<fieldset class="input">
	<div class="form-group">
		<label for="modlgn_email"><?= translate('Email') ?>:</label>
        <input id="modlgn_email" class="required validate-email form-control" type="email" name="email" />
	</div>
	<div class="form-group">
		<label for="modlgn_passwd"><?= translate('Password') ?>:</label>
        <input id="modlgn_passwd" class="required form-control" type="password" name="password" />
        <? if ($user_route = helper('route.user', array('layout' => 'reset', 'access' => 0))): ?>
            <span class="help-block">
                <a href="<?= $user_route ?>"><?= translate('Forgot your password?'); ?></a>
            </span>
        <? endif; ?>
	</div>
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn btn-primary" value="<?= translate('Sign in') ?>" />
        <?= translate('or') ?>
		<a href="<?= helper('route.user', array('layout' => 'register', 'access' => 0)); ?>"><?= translate('Sign up'); ?></a>
	</div>
	</fieldset>

	<esi:include src="http://nooku.dev/6-aenean-pellentesque?tmpl=raw" />
</form>