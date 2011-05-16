<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2009 - 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ( $params->def( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?= @escape($params->get('pageclass_sfx')); ?>">
		<?= @escape($params->get('page_title')); ?>
	</div>
<? endif; ?>

<? if ( ($params->def('image', -1) != -1) || $params->def('show_comp_description', 1) ) : ?>
<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?= @escape($params->get('pageclass_sfx')); ?>">
<tr>
	<td valign="top" class="contentdescription<?= @escape($params->get('pageclass_sfx')); ?>">
	<? if ( isset($image) ) : ?>
		<img src="<?= $image['src'] ?>" <? foreach ($image['attribs'] as $attrib => $value) : echo $attrib.'="'.$value.'" '; endforeach ?>/>
	<? endif; ?>
	<?= $params->get('comp_description'); ?>
	</td>
</tr>
</table>
<? endif; ?>
<ul>
<? foreach($categories as $category) : ?>
	<li>
		<a href="<?= @route('view=weblinks&category='. $category->id) ?>" class="category<?= @escape($params->get( 'pageclass_sfx' )); ?>">
			<?= @escape($category->title);?></a>
		&nbsp;
		<span class="small">
			(<?= $category->numlinks;?>)
		</span>
	</li>
<? endforeach; ?>
</ul>