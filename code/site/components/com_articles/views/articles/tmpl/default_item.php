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

<article <?= !$article->state ? 'class="article-unpublished"' : '' ?>>
    <div class="page-header">
        <? if ($article->editable) : ?>
        <a style="float: right;" class="btn" href="<?= @helper('route.article', array('row' => $article, 'layout' => 'form')) ?>">
            <i class="icon-edit"></i>
        </a>
        <? endif; ?>
        <h1><a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= $article->title ?></a></h1>
        <?= @helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
    </div>
    
    <? if ($article->fulltext && $params->get('show_readmore')) : ?>
        <?= $article->introtext; ?>
        <a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= @text('Read more') ?></a>
    <? else : ?>
        <?= $article->introtext . $article->fulltext ?>
    <? endif; ?>
</article>