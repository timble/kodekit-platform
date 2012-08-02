<?
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? if ($params->get('show_feed_link', 1) == 1) : ?>
	<link href="<?= @route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<? if ( $params->def( 'show_page_title', 1 ) ) : ?>
<h1><?= @escape($params->get('page_title')); ?></h1>
<? endif; ?>

<div class="clearfix">
<? if ( $category->image || $category->description ) : ?>
	<? if (isset($category->image)) : ?>
		<img src="<?= $category->image->path ?>" height="<?= $category->image->height ?>" width="<?= $category->image->width ?>" />
	<? endif; ?>
	<?= $category->description; ?>
<? endif; ?>
</div>

<?= @template('default_items'); ?>
