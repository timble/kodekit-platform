<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<form action="<?= @route('option=com_users&view=session&id='.@service('session')->getId()) ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="action" value="delete" />
    
    <?= JText::sprintf( 'HINAME', @service('user')->name ); ?>
	
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= @text('Sign out'); ?>" />
	</div>
</form>