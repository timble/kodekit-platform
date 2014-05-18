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

<form action="" method="get" class="-koowa-grid">	
<table>
	<thead>
		<tr>
			<th width="1">
                <?= helper( 'grid.checkall'); ?>
            </th>
			<th>
				<?= helper('grid.sort',  array('column' => 'name')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'count', 'title' => 'Num Files')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'size')); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
			<tr>
				<td colspan="13">
					<?= helper('com:application.paginator.pagination', array('total' => $total)); ?>
				</td>
			</tr>
		</tfoot>
	<tbody>
	<? foreach($groups as $group) : ?>
		<tr>
			<td align="center">
				<?= helper( 'grid.checkbox' , array('row' => $group)); ?>
			</td>
			<td>
				<a href="<?= route('view=keys&group='.$group->name); ?>"><?= $group->name; ?></a>
			</td>
			<td align="center">
				<?= $group->count; ?>
			</td>
			<td align="center">
				<?= number_format($group->size / 1024, 2) ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>
</form>