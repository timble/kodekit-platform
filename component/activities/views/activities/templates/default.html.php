<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<!--
<style src="media://activities/css/activities-default.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar.html'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
	<?= @template('default_scopebar.html') ?>
	<table>
		<thead>
			<tr>
				<th width="10">
                    <?= @helper('grid.checkall') ?>
                </th>
				<th width="55"><?=@text('Time')?></th>
				<th><?=@text('Message')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? $date = $old_date = '';   ?>
		<? foreach ($activities as $activity) : ?>	
	        <? $date = @date(array('date' => $activity->created_on, 'format' => 'l d M Y'))?>
	        <? if ($date != $old_date): ?>
	        <? $old_date = $date; ?>
	        <tr class="no-hover">
				<td class="activities-timeago" colspan="3">
			        <?= $date; ?>
				</td>
			</tr>
	        <? endif; ?>
			<tr>
				<td>
			        <?= @helper('grid.checkbox',array('row' => $activity)); ?>
				</td>

				<td align="left">
			        <?= @date(array('date' => $activity->created_on, 'format' => 'H:i'))?>
				</td>

				<td>
					<i class="icon-<?= $activity->action ?>"></i> <?= @helper('activity.message', array('row' => $activity))?>
				</td>
			</tr>
        <? endforeach; ?>
		</tbody>
	</table>
</form>