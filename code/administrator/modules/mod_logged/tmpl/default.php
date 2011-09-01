<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<table class="adminlist">
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
			<? if (KFactory::get('joomla:user')->authorize( 'com_users', 'manage' )) : ?>
		    	<a href="<?=  @route('index.php?option=com_users&view=user&id='. $user->id); ?>" title="<?= @text( 'Edit User' ) ?>">
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
			<?= JApplicationHelper::getClientInfo($user->loggedin_client_id)->name;?>
		</td>
		<td>
			<?= @helper('com://admin/users.template.helper.date.humanize', array('date' => $user->loggedin_on - date_offset_get(new DateTime)));?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
</table>