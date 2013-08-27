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
	<?= import('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">	
<table>
	<thead>
		<tr>
			<th width="1">
                <?= helper( 'grid.checkall'); ?>
            </th>
			<th>
				<?= helper('grid.sort',  array('column' => 'name', 'title' => 'Key')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'size')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'hits')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'created_on','title' => 'Created')); ?>
			</th>
			<th width="1">
				<?= helper('grid.sort',  array('column' => 'accessed_on','title' => 'Accessed')); ?>
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
	<? foreach($items as $item) : ?>
		<tr>
			<td align="center">
				<?= helper( 'grid.checkbox' , array('row' => $item)); ?>
			</td>
			<td>
				<span class="bold">
					<?= $item->hash; ?>
				</span>
			</td>
			<td align="center">
				<?= number_format($item->size / 1024, 2) ?>
			</td>
			<td align="center">
				<?= $item->hits; ?>
			</td>
			<td align="center">
				<?= helper('date.humanize', array('date' => $item->created_on)); ?>
			</td>
			<td align="center">
				<?= helper('date.humanize', array('date' => $item->accessed_on)); ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>
</form>