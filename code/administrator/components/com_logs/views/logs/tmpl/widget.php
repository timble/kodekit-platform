<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die( 'Restricted access' ); ?>

<style src="media://com_logs/css/logs-widget.css" />

<? if(count($logs)) : ?>
	<form action="<?= @route()?>" method="get" class="-koowa-grid">
		<table class="adminlist" style="clear: both;">
			<tbody>
				<?
				$timeago = '';
				foreach ($logs as $log) : 
					$timeago_text = @timeago($log->created_on);
					$show_timeago = ($timeago != $timeago_text);
					$timeago = $timeago_text;

				?>
					<?php if ($show_timeago): ?>
						<tr>
							<td class="-logs-timeago" colspan="4">
								<?=($show_timeago) ? $timeago_text: ''?>
							</td>
						</tr>
					<?php endif ?>

					<tr>
						<td class="-logs-message">
							On <strong><?=@date($log->created_on)?></strong>,

							<span class="-logs-createdby"><?=$log->created_by_name?></span> 

							performed 
							
							<span class="-logs-action"><?=ucfirst($log->action)?></span> 

							in 

							<span class="-logs-package"><?=ucfirst($log->package)?></span> 

							<?=@text('Component')?>&rsquo;s <?=ucfirst($log->name)?> - <a href="<?=@route('index.php?option=com_'.$log->package.'&view='.$log->name.'&id='.$log->row_id)?>" target="new"><?=$log->title?></a>.
						</td>
					</tr>
				<? endforeach; ?>
			</tbody>
			
			<tfoot>
				<tr>
					<td colspan="20">
						<a href="<?=@route('index.php?option=com_logs&view=logs&layout=default')?>">View All</a>
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
<? endif ?>