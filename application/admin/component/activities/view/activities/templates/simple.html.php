<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? $activities = object('com:activities.model.activities')->package($package)->name($name)->limit('10')->getRowset() ?>

<h3><?= translate('Recent Activities')?></h3>
<div class="scrollable">
<? if(count($activities)) : ?>
    <?
    foreach ($activities as $activity) {
    	$list[substr($activity->created_on, 0, 10)][] = $activity;
    }
    ?>
    
    <div id="activities-list">
    	<? foreach($list as $date => $activities) : ?>
    		<h4><?= helper('date.humanize', array('date' => $date)) ?></h4>
    		<div class="activities">
    			<? foreach($activities as $activity) : ?>
    			<div class="activity">
    				<i class="icon-<?= $activity->action ?>"></i>
    				<div>
    					<div class="ellipsis">
    						<a href="<?= route('view=article&id='.$activity->row) ?>">
    						   <?= escape($activity->title) ?>
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
    	<div class="btn-group">
	    	<a class="btn btn-block" href="<?= route('option=com_activities&view=activities&package='.$package) ?>">
	    	   <?= translate('More activities') ?>
	    	</a>
    	</div>
    </div>
<? endif ?>
</div>