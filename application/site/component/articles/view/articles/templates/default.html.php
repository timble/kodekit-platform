<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<title content="replace"><?= $category->title ?></title>

<? if ($params->get('show_feed_link', 1) == 1) : ?>
<link href="<?= route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif ?>

<? foreach ($articles as $article): ?>
    <?= import('default_item.html', array('article' => $article)) ?>
<? endforeach; ?>

<?= helper('paginator.pagination', array('show_limit' => false, 'show_count' => false)); ?>