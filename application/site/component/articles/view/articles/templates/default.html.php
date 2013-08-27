<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<title content="replace"><?= $category->title ?></title>

<? if ($params->get('show_feed_link', 1) == 1) : ?>
<link href="<?= route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<? foreach ($articles as $article): ?>
    <?= import('default_item.html', array('article' => $article)) ?>
<? endforeach; ?>

<?= helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)); ?>

