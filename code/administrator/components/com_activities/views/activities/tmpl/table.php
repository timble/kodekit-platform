<?php
/**
 * @version     $Id: form.php 3040 2011-10-10 00:38:18Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_activities/css/activities-widget.css" />

<? if(count($activities)) : ?>
	<? foreach ($activities as $activity) :
		$list[substr($activity->created_on, 0, 10)][] = $activity;
	endforeach; ?>

	<div id="activities-activities-widget">
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
			<? foreach($list as $date => $activities) : ?>
			<? foreach($activities as $activity) : ?>
			<tr class="<?php echo 'row'.$m; ?>" style="line-height: 14px;">
				<td><?= @helper('com://admin/activities.template.helper.activity.message', array('row' => $activity, 'truncate' => true)) ?></td>
			</tr>
			<? $i = $i + 1; $m = (1 - $m); ?>	
			<? endforeach ?>
			<? endforeach ?>
			</tbody>
		</table>
	</div>
<? endif ?>