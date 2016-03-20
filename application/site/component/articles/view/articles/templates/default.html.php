<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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