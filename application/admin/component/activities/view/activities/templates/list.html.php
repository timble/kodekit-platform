<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<div id="activities-list">
    <? if(count($activities)) : ?>
    <? foreach ($activities as $activity) : ?>
       <?$list[substr($activity->created_on, 0, 10)][] = $activity; ?>
    <? endforeach; ?>

    <? $now = object('lib:date')->format('Y-m-d') ?>

    <? foreach($list as $date => $activities) : ?>
        <h4><?= $date == $now ? translate('Today') : helper('date.humanize', array('date' => $date)) ?></h4>
        <? foreach($activities as $activity) : ?>
        <div class="activity">
            <div class="activity__text">
                <i class="icon-<?= $activity->image ?>"></i>
                <?= helper('activity.activity', array('entity' => $activity, 'url' => route())) ?>
            </div>
            <div class="activity__info">
                <?= helper('date.format', array('date' => $activity->created_on, 'format' => 'H:i'))?>
            </div>
        </div>
        <? endforeach ?>
    <? endforeach ?>
	<? endif; ?>
</div>