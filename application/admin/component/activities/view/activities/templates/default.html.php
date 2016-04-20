<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<ktml:block prepend="sidebar">
	<?= import('default_sidebar.html'); ?>
</ktml:block>

<form action="" method="get" class="-koowa-grid">
	<?= import('default_scopebar.html') ?>
	<table>
		<thead>
			<tr>
				<th width="10">
                    <?= helper('grid.checkall') ?>
                </th>
				<th width="55"><?=translate('Time')?></th>
				<th><?=translate('Message')?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					<?= helper('com:theme.paginator.pagination') ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? $date = $old_date = '';   ?>
		<? foreach ($activities as $activity) : ?>
	        <? $date = helper('date.format', array('date' => $activity->created_on, 'format' => 'l d M Y'))?>
	        <? if ($date != $old_date): ?>
	        <? $old_date = $date; ?>
	        <tr class="no-hover separator">
				<td colspan="3">
			        <?= $date; ?>
				</td>
			</tr>
	        <? endif; ?>
			<tr>
				<td>
			        <?= helper('grid.checkbox',array('entity' => $activity)); ?>
				</td>

				<td align="left">
			        <?= helper('date.format', array('date' => $activity->created_on, 'format' => 'H:i'))?>
				</td>

				<td>
					<i class="icon-<?= $activity->image ?>"></i> <?= helper('activity.activity', array('entity' => $activity))?>
				</td>
			</tr>
        <? endforeach; ?>
		</tbody>
	</table>
</form>