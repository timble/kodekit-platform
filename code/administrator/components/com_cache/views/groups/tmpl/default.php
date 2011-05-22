<? /** $Id: default.php 537 2011-03-08 20:59:09Z johanjanssens $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
 
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route() ?>" method="get" class="-koowa-grid">	
<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="10">	
			</th>
			<th class="title" nowrap="nowrap">
				<?= @helper('grid.sort',  array('column' => 'name')); ?>
			</th>
			<th width="5%" align="center" nowrap="nowrap">
				<?= @helper('grid.sort',  array('column' => 'count', 'title' => 'Num Files')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'size')); ?>
			</th>
		</tr>
		<tr>
			<td align="center">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count( $groups );?>);" />
			</td>
			<td>
				<?= @template('admin::com.default.view.grid.search_form') ?>
			</td>
			<td align="center"> 
				
			</td>
			<td></td>
		</tr>
	</thead>
	<tfoot>
			<tr>
				<td colspan="13">
					<?= @helper('paginator.pagination', array('total' => $total)); ?>
				</td>
			</tr>
		</tfoot>
	<tbody>
	<? foreach($groups as $group) : ?>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkbox' , array('row' => $group)); ?>
			</td>
			<td>
				<a href="<?= @route('view=keys&group='.$group->name); ?>"><?= $group->name; ?></a>
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