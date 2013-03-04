<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>
<article class="clearfix">
    <div class="page-header">
        <h1><a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= @highlight($article->title) ?></a></h1>
        <?= @helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
        <? if (!$article->published) : ?>
        <span class="label label-info"><?= @text('Unpublished') ?></span>
        <? endif ?>
        <? if ($article->access) : ?>
        <span class="label label-important"><?= @text('Registered') ?></span>
        <? endif ?>
    </div>
    
    <?= @helper('com://site/attachments.template.helper.grid.thumbnails', array('filter' => array('row' => $article->id, 'table' => 'articles'), 'attribs' => array('class' => 'thumbnail', 'align' => 'right', 'style' => 'margin:0 0 20px 20px;'))); ?>
    
    <? if ($article->introtext) : ?>
        <?= @highlight($article->introtext) ?>
        <a href="<?= @helper('route.article', array('row' => $article)) ?>"><?= @text('Read more') ?></a>
    <? endif; ?>
</article>