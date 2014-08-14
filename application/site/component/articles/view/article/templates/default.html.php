<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<title content="replace"><?= $article->title ?></title>

<? if ($params->get('commentable')) : ?>
    <link href="<?= route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <header>
	    <? if (object('dispatcher')->getController()->canEdit()) : ?>
        <div class="btn-toolbar">
            <ktml:toolbar type="actionbar">
        </div>
	    <? endif; ?>
	    <h1><?= $article->title ?></h1>
	    <?= helper('date.timestamp', array('entity' => $article, 'show_modify_date' => false)); ?>
	    <? if (!$article->published) : ?>
	    <span class="label label-info"><?= translate('Unpublished') ?></span>
	    <? endif ?>
	    <? if ($article->access) : ?>
	    <span class="label label-important"><?= translate('Registered') ?></span>
	    <? endif ?>
	</header>

    <?= helper('com:attachments.image.thumbnail', array(
        'attachment' => $article->attachments_attachment_id,
        'attribs' => array('width' => '200', 'align' => 'right', 'class' => 'thumbnail'))) ?>

    <? if($article->fulltext) : ?>
        <div class="article__introtext">
            <?= $article->introtext ?>
        </div>
    <? else : ?>
        <?= $article->introtext ?>
    <? endif ?>

    <?= $article->fulltext ?>

    <? if($article->isTaggable()) : ?>
    <?= import('com:tags.view.tags.default.html', array('tags' => $article->getTags())) ?>
    <? endif; ?>

    <? if($article->isAttachable()) : ?>
    <?= import('com:attachments.view.attachments.default.html', array('attachments' => $article->getAttachments(), 'exclude' => array($article->attachments_attachment_id))) ?>
    <? endif ?>
</article>

<? if($article->id && $params->get('commentable')) : ?>
    <?= object('com:articles.controller.comment')->row($article->id)->sort('created_on')->render(array('entity' => $article));?>
<? endif ?>
