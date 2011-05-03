<? /** $Id: default.php 1859 2010-12-18 20:37:31Z johanjanssens $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_logs/css/logs-default.css" />

<div id="list" class="-koowa-box-flex">
	<form action="<?= @route()?>" method="get" class="-koowa-box-flex">
		<table class="adminlist" style="clear: both;">
			<thead>
				<tr>
					<th width="5">
						<?= @text('NUM') ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Applicaton', 'column' => 'application')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Type', 'column' => 'type')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Package', 'column' => 'package')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Name', 'column' => 'name')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Action', 'column' => 'action')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Created by', 'column' => 'created_by')) ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Created on', 'column' => 'created_on')) ?>
					</th>
				</tr>
				<tr class="filters">
					<td>
						<?= @text('Filters') ?>	
					</td>
					<td>
						<?= @helper('listbox.applications',  array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
					</td>
					<td>
						<?= @helper('listbox.types',  array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
					</td>
					<td>
						<?= @helper('listbox.packages',  array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
					</td>
					<td>
						<?= @helper('listbox.names',  array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
					</td>
					<td>
						<?= @helper('listbox.actions',  array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
					</td>
					<td colspan="2">
					</td>
				</tr>
			</thead>
			
			<tbody>
				<? if (count($logs)) : ?>
					<?= @template('default_logs'); ?>
				<? else : ?>
				<tr>
					<td colspan="8" align="center">
						<?= @text('No items found'); ?>
					</td>
				</tr>
				<? endif; ?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="20">
						<?= @helper('paginator.pagination', array('total' => $total)) ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>