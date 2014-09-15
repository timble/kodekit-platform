<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="_action" value="delete" />
    
    <?= translate( 'Hi {name}', array('name' => object('user')->getName())); ?>
	
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= translate('Sign out'); ?>" />
	</div>
</form>