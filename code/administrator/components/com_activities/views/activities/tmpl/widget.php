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

<style src="media://com_activities/css/activities-widget.css" />

<? if(count($activities)) : ?>
	<form action="<?= @route()?>" method="get" class="-koowa-grid">
		<table class="adminlist" style="clear: both;">
			<tbody>
				<?
				$timeago = '';
				foreach ($activities as $activity) : 
					$timeago_text = @timeago($activity->created_on);
					$show_timeago = ($timeago != $timeago_text);
					$timeago = $timeago_text;

				?>
					<?php if ($show_timeago): ?>
						<tr>
							<td class="activities-timeago" colspan="4">
								<?=($show_timeago) ? $timeago_text: ''?>
							</td>
						</tr>
					<?php endif ?>

					<tr>
						<td class="activities-message">
							On <strong><?=@date($activity->created_on)?></strong>,

							<span class="activities-createdby"><?=$activity->created_by_name?></span> 

							performed 
							
							<span class="activities-action"><?=ucfirst($activity->action)?></span> 

							in 

							<span class="activities-package"><?=ucfirst($activity->package)?></span> 

							<?=@text('Component')?>&rsquo;s <?=ucfirst($activity->name)?> - <a href="<?=@route('option=com_'.$activity->package.'&view='.$activity->name.'&id='.$activity->row_id)?>" target="new"><?=$activity->title?></a>.
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="20">
						<a href="<?=@route('option=com_activities&view=logs&layout=default')?>">View All</a>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
<? endif ?>