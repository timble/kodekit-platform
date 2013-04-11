<?
/**
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

<? if($category->image || $category->description) : ?>
<div class="clearfix well">
    <? if ($category->image) : ?>
    <?= @helper('com:categories.string.image', array('row' => $category)) ?>
    <? endif; ?>
    
    <? if ($category->description) : ?>
    <?= $category->description; ?>
    <? endif ?>
</div>
<? endif; ?>

<?= @template('default_items.html'); ?>

<?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)) ?>
