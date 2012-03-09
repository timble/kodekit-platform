<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_activities/css/activities-list.css" />

<div id="activities-list">
    <div>
        <?= @template('default_filter') ?>
    </div>
    <? if(count($activities)) : ?>
    <div class="activities">
	    <? foreach ($activities as $activity) : ?>
	       <? $list[substr($activity->created_on, 0, 10)][] = $activity; ?>
	    <? endforeach; ?>
	
	    <? foreach($list as $date => $activities) : ?>
			<h4><?= @helper('date.humanize', array('date' => $date)) ?></h4>
			<div class="activities-day">
			    <? foreach($activities as $activity) : ?>
				<div class="activity">
					<i class="icon-<?= $activity->action ?>"></i>
				    <?= @helper('com://admin/activities.template.helper.activity.message', array('row' => $activity)) ?>
					<span class="info">
						<small><?= $activity->package.' - '.$activity->name ?> | <?= date("H:i", strtotime($activity->created_on)) ?></small>
					</span>
				</div>
			    <? endforeach ?>
			</div>
	    <? endforeach ?>
	</div>
	<? endif; ?>
</div>