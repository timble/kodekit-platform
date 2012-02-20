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

defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= @route('option=com_users&view=user&id='.$user->id) ?>" method="post" name="login" id="form-login">
	<input type="hidden" name="action" value="logout" />
    <? if ($show_greeting) : ?>
	<div>
	    <? if ($name) : ?>
		    <?= JText::sprintf( 'HINAME', $user->name ); ?>
	    <? else : ?>
		    <?= JText::sprintf( 'HINAME', $user->username ); ?>
	    <? endif; ?>
	</div>
    <? endif; ?>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?= @text( 'BUTTON_LOGOUT'); ?>" />
	</div>
</form>