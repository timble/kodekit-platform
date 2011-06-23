<? /** $Id: default.php 537 2011-03-08 20:59:09Z johanjanssens $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
 
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @toolbar(); ?>

<form action="<?= @route() ?>" method="get" class="-koowa-grid">	
<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="10">	
			</th>
			<th class="title" nowrap="nowrap">
				<?= @helper('grid.sort',  array('column' => 'name', 'title' => 'Key')); ?>
			</th>
			<th width="5%" align="center" nowrap="nowrap">
				<?= @helper('grid.sort',  array('column' => 'group')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'size')); ?>
			</th>
			<th width="5%" align="center">
				<?= @helper('grid.sort',  array('column' => 'hits')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'created_on','title' => 'Created')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'accessed_on','title' => 'Accessed')); ?>
			</th>
		</tr>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkall'); ?>
			</td>
			<td>
				<?= @helper( 'grid.search'); ?>
			</td>
			<td align="center"> 
				<?= @helper('listbox.groups'); ?>
			</td>
			<td></td>
			<td></td>
			<td></td>
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
	<? foreach($keys as $key) : ?>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkbox' , array('row' => $key)); ?>
			</td>
			<td>
				<span class="bold">
					<?= $key->hash; ?>
				</span>
			</td>
			<td>
				<?= $key->group; ?>
			</td>
			<td align="center">
				<?= number_format($key->size / 1024, 2) ?>
			</td>
			<td align="center">
				<?= $key->hits; ?>
			</td>
			<td align="center">
				<?= @helper('date.humanize', array('date' => $key->created_on)); ?>
			</td>
			<td align="center">
				<?= @helper('date.humanize', array('date' => $key->accessed_on)); ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>