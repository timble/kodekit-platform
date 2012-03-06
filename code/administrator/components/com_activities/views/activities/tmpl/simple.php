<?php
/**
 * @version     $Id: list.php 1485 2012-02-10 12:32:02Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?
foreach ($activities as $activity) {
	$list[substr($activity->created_on, 0, 10)][] = $activity;
}
?>

<div id="activities-list" class="-koowa-box-vertical">
	<? foreach($list as $date => $activities) : ?>
		<h4><?= @helper('date.humanize', array('date' => $date)) ?></h4>
		<div class="activities">
			<? foreach($activities as $activity) : ?>
			<div class="activity">
				<i class="icon-<?= $activity->action ?>"></i>
				<div>
					<div class="ellipsis">
						<a href="<?= @route('view=article&id='.$activity->row) ?>">
						   <?= @escape($activity->title) ?>
						</a>
					</div>
					 <div class="ellipsis">
					 	<small class="datetime">
					 		<?= date("H:i", strtotime($activity->created_on)) ?> - <?= $activity->created_by_name ?>
					 	</small>
					 </div>
				</div>
			</div>
			<? endforeach ?>
		</div>
	<? endforeach ?>
</div>