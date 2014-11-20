<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<div id="activities-list">
    <? if(count($activities)) : ?>
    <? foreach ($activities as $activity) : ?>
       <? $list[substr($activity->created_on, 0, 10)][] = $activity; ?>
    <? endforeach; ?>

    <? foreach($list as $date => $activities) : ?>
        <h4><?= helper('date.humanize', array('date' => $date)) ?></h4>
        <? foreach($activities as $activity) : ?>
        <div class="activity">
            <div class="activity__text">
                <i class="icon-<?= $activity->action ?>"></i>
                <?= helper('activity.message', array('entity' => $activity)) ?>
            </div>
            <div class="activity__info">
                <?= $activity->package.' - '.$activity->name ?> | <?= helper('date.format', array('date' => $activity->created_on, 'format' => 'H:i'))?>
            </div>
        </div>
        <? endforeach ?>
    <? endforeach ?>
	<? endif; ?>
</div>