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

<? if ($article->editable) : ?>
<div class="edit-article">
    <a href="<?= @helper('route.article', array('row' => $article, 'layout' => 'form')) ?>">
        <?= @text('Edit') ?>
     </a>
    </div>
<? endif; ?>

<article <?= !$article->state ? 'class="article-unpublished"' : '' ?>>

    <h1><?= $article->title ?></h1>

    <p class="timestamp">
        <?= @helper('date.timestamp', array('row' => $article)); ?>
    </p>
    
    <?= $article->introtext . $article->fulltext ?>
</article>