<?
/**
 * @version		$Id: default.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ( $params->get( 'show_page_title', 1 ) ) : ?>
	<div class="componentheading<?= @escape($params->get('pageclass_sfx')); ?>"><?= @escape($params->get('page_title')); ?></div>
<? endif; ?>

<table width="100%" cellpadding="4" cellspacing="0" border="0" align="center" class="contentpane<?= @escape($params->get('pageclass_sfx')); ?>">
<? if ( $category->image || $category->description ) : ?>
<tr>
	<td valign="top" class="contentdescription<?= @escape($params->get('pageclass_sfx')); ?>">
	<? if (isset($category->image)) : ?>
		<img src="<?= $category->image['src'] ?>" <? foreach ($category->image['attribs'] as $attrib => $value) : echo $attrib.'="'.$value.'" '; endforeach ?>/>
	<? endif; ?>
	<?= $category->description; ?>
	</td>
</tr>
<? endif; ?>
<tr>
	<td width="60%" colspan="2">
	<?= @template('default_items'); ?>
	</td>
</tr>
</table>
