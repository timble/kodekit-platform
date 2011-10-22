<?php
/**
 * @version     $Id: form.php 3040 2011-10-10 00:38:18Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_logs/css/logs-widget.css" />

<? if(count($logs)) : ?>
	<? foreach ($logs as $log) :
		$list[substr($log->created_on, 0, 10)][] = $log;
	endforeach; ?>

	<div id="logs-logs-widget">
		<table class="logs" style="clear: both;">
			<thead>
				<tr>	
					<th style="text-align: left;">
						<?= @text('Log'); ?>
					</th>
				</tr>
			</thead>
			<tbody>	
			<? $i = 0; $m = 0; ?>
			<? foreach($list as $date => $logs) : ?>
			<? foreach($logs as $log) : ?>
			<tr class="<?php echo 'row'.$m; ?>" style="line-height: 14px;">
				<td><?= @helper('com://admin/activities.template.helper.message.build', array('row' => $log, 'truncate' => true)) ?></td>
			</tr>
			<? $i = $i + 1; $m = (1 - $m); ?>	
			<? endforeach ?>
			<? endforeach ?>
			</tbody>
		</table>
	</div>
<? endif ?>