<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:script src="assets://js/koowa.js" />
<ktml:style src="assets://css/koowa.css" />

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<ktml:block prepend="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:block>

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
					<?= helper('grid.sort',  array('title' => 'Logged In', 'column' => 'authentic')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort',  array('title' => 'Role', 'column' => 'role')) ?>
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
					<?= helper('com:application.paginator.pagination') ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? $i = 0 ?>
		<? foreach($users as $user) : ?>
			<tr>
				<td align="center">
					<?= helper('grid.checkbox' , array('entity' => $user)) ?>
				</td>
                <td align="center">
                    <?= helper('grid.enable', array('entity' => $user, 'component' => 'users', 'view' => 'users')) ?>
                </td>
				<td>
					<a href="<?= route('view=user&id='.$user->id) ?>">
						<?= escape($user->name) ?>
					</a>
				</td>
				<td align="center">
					<i class="<?= $user->authentic ? 'icon-ok' : 'icon-remove' ?>"></i>
				</td>
				<td>
					<?= escape($user->role) ?>
				</td>
				<td class="array-separator">
                    <?= helper('grid.groups', array('groups' => $user->getGroups())) ?>
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