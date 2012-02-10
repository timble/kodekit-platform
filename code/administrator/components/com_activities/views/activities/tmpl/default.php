<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_activities/css/activities-default.css" />

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<?= @template('default_sidebar')?>

<form action="" method="get" class="-koowa-grid">
	<?= @template('default_filter') ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="10" align="center">
                    <?= @helper('grid.checkall') ?>
                </th>
				<th class="activities-time"><?=@text('Time')?></th>
				<th class="activities-message"><?=@text('Message')?></th>
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
	        <? $date = @date(array('date' => $activity->created_on, 'format' => '%d %b %Y'))?>
	        <? if ($date != $old_date): ?>
	        <? $old_date = $date; ?>
	        <tr>
				<td class="activities-timeago" colspan="3">
			        <?= $date; ?>
				</td>
			</tr>
	        <? endif; ?>
			<tr>
				<td>
			        <?= @helper('grid.checkbox',array('row' => $activity)); ?>
				</td>

				<td align="left" class="activities-when">
			        <?= @date(array('date' => $activity->created_on, 'format' => '%l:%M%p'))?>
				</td>

				<td class="activities-message">
					<?= @helper('activity.message', array('row' => $activity))?>
				</td>
			</tr>
        <? endforeach; ?>
		</tbody>
	</table>
</form>