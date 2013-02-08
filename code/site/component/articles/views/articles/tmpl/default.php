<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<? if ($params->get('show_feed_link', 1) == 1) : ?>
<link href="<?= @route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<? foreach ($articles as $article): ?>
    <?= @template('default_item', array('article' => $article)) ?>
<? endforeach; ?>

<? if(count($articles) != $total) : ?>
    <?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)); ?>
<? endif; ?>

