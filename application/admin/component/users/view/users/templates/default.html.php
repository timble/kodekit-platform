<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<ktml:module position="sidebar">
	<?= import('default_sidebar.html') ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
	<?= import('default_scopebar.html'); ?>
	<table>
		<thead>
			<tr>
				<th width="1">
				    <?= helper('grid.checkall'); ?>
				</th>
                <th width="1"></th>
				<th>
					<?= helper('grid.sort', array('title' => 'Name', 'column' => 'name')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort',  array('title' => 'Logged In', 'column' => 'loggedin')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort',  array('title' => 'Role', 'column' => 'role_name')) ?>
				</th>
				<th width="1">
					<?= translate('Group') ?>
				</th>
				<th width="1">
					<?= helper('grid.sort',  array('title' => 'E-Mail', 'column' => 'email')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort',  array('title' => 'Last Visit', 'column' => 'last_visited_on')) ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? $i = 0 ?>
		<? foreach($users as $user) : ?>
			<tr>
				<td align="center">
					<?= helper('grid.checkbox' , array('row' => $user)) ?>
				</td>
                <td align="center">
                    <?= helper('grid.enable', array('row' => $user, 'option' => 'com_users', 'view' => 'users')) ?>
                </td>
				<td>
					<a href="<?= route('view=user&id='.$user->id) ?>">
						<?= escape($user->name) ?>
					</a>
				</td>
				<td align="center">
					<i class="<?= $user->loggedin ? 'icon-ok' : 'icon-remove' ?>"></i>
				</td>
				<td>
					<?= escape($user->role_name) ?>
				</td>
				<td class="array-separator">
					<? foreach($groups_users->find(array('users_user_id' => $user->id)) as $group) : ?>
						<span><?= $group->group_name ?></span>
					<? endforeach; ?>
				</td>
				<td>
					<?= escape($user->email) ?>
				</td>
				<td>
					<? if($user->last_visited_on == '0000-00-00 00:00:00') : ?>
						<?= translate('Never') ?>
					<? else : ?>
						<?= helper('date.humanize', array('date' => $user->last_visited_on)) ?>
					<? endif ?>
				</td>
			</tr>
			<? $i++ ?>
		<? endforeach ?>
		</tbody>
	</table>
</form>