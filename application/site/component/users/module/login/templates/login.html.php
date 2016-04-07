<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.koowa'); ?>
<?= helper('behavior.validator')?>

<? if(parameters()->show_title) : ?>
    <h3><?= title() ?></h3>
<? endif ?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login" class="-koowa-form">
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

    <ktml:include src="com:articles.controller.article?id=2" />
</form>