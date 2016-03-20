<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<title content="replace"><?= $article->title ?></title>

<? if ($params->get('commentable')) : ?>
    <link href="<?= route('format=rss') ?>" rel="alternate" type="application/rss+xml" />
<? endif; ?>

<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <header>
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
        <? foreach($article->getTags() as $tag) : ?>
            <span class="label"><?= $tag->title ?></span>
        <? endforeach ?>
    <? endif; ?>

    <? if($article->isAttachable()) : ?>
    <?= import('com:attachments.attachments.default.html', array('attachments' => $article->getAttachments(), 'exclude' => array($article->attachments_attachment_id))) ?>
    <? endif ?>
</article>

<? if($article->id && $params->get('commentable')) : ?>
    <?= object('com:articles.controller.comment')->row($article->id)->sort('created_on')->render(array('entity' => $article));?>
<? endif ?>
