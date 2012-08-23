<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<table class="table">
<thead>
	<tr>
		<th>
			<strong><?= @text( 'Name' ); ?></strong>
		</th>
		<th>
			<strong><?= @text( 'User Group' ); ?></strong>
		</th>
		<th>
			<strong><?= @text( 'Application' ); ?></strong>
		</th>
		<th>
			<strong><?= @text( 'Last Activity' ); ?></strong>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($users as $user) : ?>
   <tr>
		<td>
			<? if (JFactory::getUser()->authorize( 'com_users', 'manage' )) : ?>
		    	<a href="<?=  @route('option=com_users&view=user&id='. $user->id); ?>" title="<?= @text( 'Edit User' ) ?>">
		    		<?= $user->username; ?>
		    	</a>
		       <? else : ?>
		           <?= $user->username; ?>
		       <? endif; ?>
		</td>
		<td>
			<?= $user->group_name;?>
		</td>
		<td>
			<?= $user->loggedin_application; ?>
		</td>
		<td>
			<?= @helper('com://admin/users.template.helper.date.humanize', array('date' => '@'.$user->loggedin_on));?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>