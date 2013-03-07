<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>
<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <div class="page-header">
	    <? if ($article->editable) : ?>
	    <a style="float: right;" class="btn" href="<?= @helper('route.article', array('row' => $article, 'layout' => 'form')) ?>">
	        <i class="icon-edit"></i>
	    </a>
	    <? endif; ?>
	    <h1><?= $article->title ?></h1>
	    <?= @helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
	    <? if (!$article->published) : ?>
	    <span class="label label-info"><?= @text('Unpublished') ?></span>
	    <? endif ?>
	    <? if ($article->access) : ?>
	    <span class="label label-important"><?= @text('Registered') ?></span>
	    <? endif ?>
	</div>

    <? if($article->thumbnail): ?>
        <img src="<?= $article->thumbnail ?>" class="thumbnail" style="float: left" />
    <? endif; ?>

    <?= $article->introtext . $article->fulltext ?>

    <?= @helper('com://site/attachments.template.helper.grid.thumbnails', array('exclude' => array($article->image), 'filter' => array('row' => $article->id, 'table' => 'articles'), 'attribs' => array('class' => 'thumbnail', 'align' => 'right', 'style' => 'margin:0 0 20px 20px;'))); ?>

    <?= @helper('com://site/attachments.template.helper.grid.files', array('filter' => array('row' => $article->id, 'table' => 'articles'))); ?>
</article>