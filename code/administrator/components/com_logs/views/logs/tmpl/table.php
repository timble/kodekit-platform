<? defined('KOOWA') or die('Restricted access'); ?>

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
				<td><?= @helper('admin::com.logs.template.helper.message.build', array('row' => $log, 'truncate' => true)) ?></td>
			</tr>
			<? $i = $i + 1; $m = (1 - $m); ?>	
			<? endforeach ?>
			<? endforeach ?>
			</tbody>
		</table>
	</div>
<? endif ?>