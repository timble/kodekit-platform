<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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