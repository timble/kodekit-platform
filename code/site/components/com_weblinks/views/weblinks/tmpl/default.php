<?
/**
 * @version		$Id$
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? if ($params->get('show_feed_link', 1) == 1) : ?>
	<link href="<?= @route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<div class="page-header">
    <h1><?= @escape($params->get('page_title')); ?></h1>
</div>

<? if(!empty($category->image) || !empty($category->description)) : ?>
<div class="clearfix well">
	<? if (!empty($category->image)) : ?>
		<?= @helper('com://site/categories.template.helper.string.image', array('row' => $category)) ?>
	<? endif; ?>
	
	<? if (!empty($category->description)) : ?>
	<p class="lead"><?= $category->description; ?></p>
	<? endif ?>
</div>
<? endif; ?>

<?= @template('default_items'); ?>

<?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)) ?>
