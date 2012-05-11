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
	<div class="page-header"><h1><?= $category->title; ?></h1></div>
<? endif; ?>

<? if ( $category->image || $category->description ) : ?>
	<? if (isset($category->image)) : ?>
		<img src="<?= $category->image['src'] ?>" <? foreach ($category->image['attribs'] as $attrib => $value) : echo $attrib.'="'.$value.'" '; endforeach ?>/>
	<? endif; ?>
	<?= $category->description; ?>
<? endif; ?>

<?= @template('default_items'); ?>
