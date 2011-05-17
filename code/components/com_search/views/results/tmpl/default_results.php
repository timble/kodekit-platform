<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<table class="contentpaneopen<?=@escape($params->get('pageclass_sfx')); ?>">
	<tr>
		<td>
		<? $i = 1;
		foreach( $results as $result ) : ?>
			<fieldset>
				<div>
					<span class="small<?=@escape($params->get('pageclass_sfx')); ?>">
						<?=$offset + $i.'. ';?>
					</span>
					<? if ( $result->href ) :
						if ($result->browsernav == 1 ) : ?>
							<a href="<?=@route($result->href); ?>" target="_blank">
						<? else : ?>
							<a href="<?=@route($result->href); ?>">
						<? endif;

						echo @escape($result->title);

						if ( $result->href ) : ?>
							</a>
						<? endif;
						if ( $result->section ) : ?>
							<br />
							<span class="small<?=@escape($params->get('pageclass_sfx')); ?>">
								(<?=@escape($result->section); ?>)
							</span>
						<? endif; ?>
					<? endif; ?>
				</div>
				<div>
					<?=$result->text; ?>
				</div>
				<?
					if ( $params->get( 'show_date' )) : ?>
				<div class="small<?=@escape($params->get('pageclass_sfx')); ?>">
					<?=$result->created; ?>
				</div>
				<? endif; ?>
			</fieldset>
		<? $i++;
		endforeach;?>
		</td>
	</tr>
</table>
<div class="search-pagination"><?= @helper('paginator.pagination', array('total' => $total)) ?><div class="clear_both"></div></div>		


