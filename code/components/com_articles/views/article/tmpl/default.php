<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_articles/css/site.css"/>

<? if ($article->editable) : ?>
<div class="edit-article">
    <a href="<?= @helper('route.article', array('row' => $article, 'layout' => 'form')) ?>">
        <?= @text('Edit') ?>
     </a>
    </div>
<? endif; ?>

<div class="clear_both"></div>

<article <?= !$article->state ? 'class="article-unpublished"' : '' ?>>

    <h1><?= $article->title ?></h1>

    <p class="timestamp">
        <?= @helper('date.timestamp', array('row' => $article)); ?>
    </p>

    <? if ($article->fulltext) : ?>

        <?= $article->introtext; ?>
        <a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= @text('Read more') ?></a>

    <? else : ?>

        <?= $article->introtext . $article->fulltext ?>

    <? endif; ?>

</article>