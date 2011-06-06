<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<div id="list" class="-koowa-box-flex">
	<form action="<?= @route('option=com_'.$state->package)?>" method="get" class="-koowa-box-flex -koowa-grid">
		<table class="adminlist" style="clear: both;">
			<thead>
				<tr>
					<th width="5">
						<?= @helper('grid.checkall') ?>
					</th>
					<th>
						<?= @helper('grid.sort', array('title' => 'Applicaton', 'column' => 'application')) ?>
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
						<?= @helper('listbox.applications',  array('attribs' => array('onchange' => 'this.form.submit();'), 'selected' => $state->application)) ?>
					</td>
					<td>
						<?= @helper('listbox.names',  array('attribs' => array('onchange' => 'this.form.submit();'), 'selected' => $state->name)) ?>
					</td>
					<td>
						<?= @helper('listbox.actions',  array('attribs' => array('onchange' => 'this.form.submit();'), 'selected' => $state->action)) ?>
					</td>
					<td colspan="2">
					</td>
				</tr>
			</thead>
			
			<tbody>
				<? if (count($logs)) : ?>
					<? foreach ($logs as $log) : ?>
                        <tr>
                            <td align="center">
                                <?= @helper('grid.checkbox',array('row' => $log)); ?>
                            </td>
                            <td>
                                <?= $log->application ?>
                            </td>
                            <td>
                                <?= $log->name ?>
                            </td>
                            <td>
                                <?= $log->action ?>
                            </td>
                            <td>
                                <?= $log->created_by_name ?>
                            </td>
                            <td>
                                <?= $log->created_on ?>
                            </td>
                        </tr>
                        <? endforeach; ?>
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