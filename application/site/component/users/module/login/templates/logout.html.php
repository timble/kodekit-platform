<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<form action="<?= helper('route.session'); ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="_action" value="delete" />
    
    <?= JText::sprintf( 'HINAME', object('user')->getName()); ?>
	
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= translate('Sign out'); ?>" />
	</div>
</form>