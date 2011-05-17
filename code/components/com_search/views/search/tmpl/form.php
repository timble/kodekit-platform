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

<? if ($params->get( 'show_page_title', 1 )) : ?>
<div class="componentheading<?= @escape( $params->get( 'pageclass_sfx' ) );?>">
	<?= $params->get( 'page_title', @text( 'Search' ) );?>
</div>
<? endif; ?>

<form id="searchForm" action="index.php" method="get" name="searchForm">
	<table class="contentpaneopen<?= @escape($params->get('pageclass_sfx')); ?>">
		<tr>
			<td nowrap="nowrap">
				<label for="search_searchword">
					<?= @text( 'Search Keyword' ); ?>:
				</label>
			</td>
			<td nowrap="nowrap">
				<input type="text" name="keyword" id="keyword" maxlength="20" size="30" value="<?= @escape($keyword);?>" class="inputbox" />
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
			<?= @text( 'Search Keyword' ) .' <b>'. @escape($keyword) .'</b>'; ?>
		</td>
	</tr>
	<tr>
		<td>
			<br />
			<?= JText::sprintf('TOTALRESULTSFOUND',$total); ?>
		</td>
	</tr>
</table>
<?if ($results) :?>
<?= @template( 'results' );?>
<? endif;?>
<input type="hidden" name="option" value="com_search" />
<input type="hidden" name="view" value="search" />
<input type="hidden" name="Itemid" value="<?=$item_id;?>" />
</form>

