<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_logs/css/logs-widget.css" />

<? if(count($logs)) : ?>
	<? foreach ($logs as $log) :
		$list[substr($log->created_on, 0, 10)][] = $log;
	endforeach; ?>

	<div id="logs-logs-widget">
		<? foreach($list as $date => $logs) : ?>
			<h4><?= @helper('date.humanize', array('date' => $date)) ?></h4>
			<? foreach($logs as $log) : ?>
			<div class="log">
				<span class="icon icon-16-<?= $log->action ?>"></span>
				<?= @helper('admin::com.logs.template.helper.message.build', array('row' => $log, 'truncate' => true)) ?>
			</div>
			<? endforeach ?>
		<? endforeach ?>
	</div>
<? endif ?>