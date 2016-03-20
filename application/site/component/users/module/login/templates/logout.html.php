<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? if(parameters()->show_title) : ?>
    <h3><?= title() ?></h3>
<? endif ?>

<? if(object('user')->getSession()->isActive()) : ?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="_action" value="delete" />

    <?= translate( 'Hi {name}', array('name' => object('user')->getName())); ?>

	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= translate('Sign out'); ?>" />
	</div>
</form>

<? endif; ?>