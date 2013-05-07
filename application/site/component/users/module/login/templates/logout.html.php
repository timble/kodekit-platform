<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<form action="<?= @helper('route.session'); ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="_action" value="delete" />
    
    <?= JText::sprintf( 'HINAME', @object('user')->getName()); ?>
	
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= @text('Sign out'); ?>" />
	</div>
</form>