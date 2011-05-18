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

<form id="searchForm" action="<?= @route() ?>" method="get" name="searchForm">
	<table class="contentpaneopen<?= @escape($params->get('pageclass_sfx')); ?>">
		<tr>
			<td nowrap="nowrap">
				<label for="search_searchword">
					<?= @text( 'Search Keyword' ); ?>:
				</label>
			</td>
			<td nowrap="nowrap">
				<input type="text" name="term" id="term" maxlength="20" size="30" value="<?= @escape($state->term);?>" class="inputbox" />
			</td>
			<td width="100%" nowrap="nowrap">
				<button onclick="this.form.submit()" class="button"><?=@text( 'Search' );?></button>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<?= @helper('site::com.search.template.helper.select.searchphrase');?>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<label for="ordering">
					<?= @text( 'Ordering' );?>:
				</label>
				<?= @helper('site::com.search.template.helper.select.ordering');?>
			</td>
		</tr>
	</table>
	<? if ($params->get( 'search_areas', 1 )) : ?>
		<?= @text( 'Search Only' );?>:
		<?= @helper('site::com.search.template.helper.select.searchareas');?>
	<? endif; ?>

	<table class="searchintro<?= @escape($params->get('pageclass_sfx')); ?>">
	<tr>
		<td colspan="3" >
			<br />
			<?= @text( 'Search Keyword' ) .' <b>'. @escape($state->term) .'</b>'; ?>
		</td>
	</tr>
	<tr>
		<td>
			<br />
			<?= JText::sprintf('TOTALRESULTSFOUND',$total); ?>
		</td>
	</tr>
</table>
</form>