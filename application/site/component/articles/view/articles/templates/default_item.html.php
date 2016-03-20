<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<article>
    <header>
        <h1><a href="<?= helper('route.article', array('entity' => $article)) ?>"><?= highlight($article->title) ?></a></h1>
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

    <? if ($article->introtext) : ?>
        <?= highlight($article->introtext) ?>
        <a class="article__readmore" href="<?= helper('route.article', array('entity' => $article)) ?>"><?= translate('Read more') ?></a>
    <? endif; ?>
</article>