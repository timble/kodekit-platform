<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<table>
	<tr>
		<td>
		<? $i = 1;
		foreach( $results as $result ) : ?>
			<fieldset>
				<div>
					<span class="small">
						<?= $state->offset + $i.'. ';?>
					</span>
					<? if ( $result->href ) : ?>
						
					    <? if ($result->browsernav == 1 ) : ?>
							<a href="<?= @route($result->href); ?>" target="_blank">
						<? else : ?>
							<a href="<?= @route($result->href); ?>">
						<? endif; ?>

						<?= @escape($result->title) ?>

						<? if ( $result->href ) : ?>
							</a>
						<? endif; ?>
						<? if ( $result->section ) : ?>
							<br />
							<span class="small">
								(<?= @escape($result->section); ?>)
							</span>
						<? endif; ?>
					<? endif; ?>
				</div>
				<div>
					<?= @helper('string.summary', array('text' => $result->text)); ?>
				</div>
				<? if ( $params->get( 'show_date' )) : ?>
				<div class="small">
					<? if($result->created) : ?>
                        <?= @helper('date.format', array('date' => $result->created)) ?>
					<? endif; ?>
				</div>
				<? endif; ?>
			</fieldset>
		<? $i++;
		endforeach;?>
		</td>
	</tr>
</table>
<div class="search-pagination"><?= @helper('paginator.pagination', array('total' => $total)) ?><div style="clear: both;"></div></div>