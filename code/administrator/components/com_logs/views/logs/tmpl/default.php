<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_logs/css/logs-default.css" />

<?= @template('default_sidebar')?>

<form action="" method="get" class="-koowa-grid">
	<?= @template('default_filter') ?>
	<table class="adminlist">
		<thead>
			<tr>
				<th width="10" align="center">
                    <?= @helper('grid.checkall') ?>
                </th>
				<th class="logs-time"><?=@text('Time')?></th>
				<th class="logs-message"><?=@text('Message')?></th>
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
		<? foreach ($logs as $log) : ?>	
	        <? $date = @date(array('date' => $log->created_on, 'format' => '%d %b %Y'))?>
	        <? if ($date != $old_date): ?>
	        <? $old_date = $date; ?>
	        <tr>
				<td class="logs-timeago" colspan="3">
			        <?= $date; ?>
				</td>
			</tr>
	        <? endif; ?>
			<tr>
				<td>
			        <?= @helper('grid.checkbox',array('row' => $log)); ?>
				</td>

				<td align="left" class="logs-when">
			        <?= @date(array('date' => $log->created_on, 'format' => '%l:%M%p'))?>
				</td>

				<td class="-logs-message">
					<span class="logs-createdby">
					    <a href="<? @route('option=com_users&view=user&id='.$log->created_by) ?>"><?= $log->created_by_name?></a>
					</span> 
					<span class="logs-action"><?= KInflector::verbalize($log->action); ?></span> 
					<span class="logs-package"><?= $log->name ?></span> 
                    <?php if ($log->action != 'delete'): ?>
						<a href="<?=@route('option=com_'.$log->package.'&view='.$log->name.'&id='.$log->row)?>"><?=$log->title?></a>
		            <?php else: ?>
						<span class="logs-deleted"><?= $log->title ?></span>
		            <?php endif ?>
				</td>
			</tr>
        <? endforeach; ?>
		</tbody>
	</table>
</form>