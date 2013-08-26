<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<article>
    <header>
        <h1><a href="<?= helper('route.article', array('row' => $article)) ?>"><?= highlight($article->title) ?></a></h1>
        <?= helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
        <? if (!$article->published) : ?>
        <span class="label label-info"><?= translate('Unpublished') ?></span>
        <? endif ?>
        <? if ($article->access) : ?>
        <span class="label label-important"><?= translate('Registered') ?></span>
        <? endif ?>
    </header>

    <?= helper('com:attachments.image.thumbnail', array('row' => $article)) ?>

    <? if ($article->introtext) : ?>
        <?= highlight($article->introtext) ?>
        <a class="article__readmore" href="<?= helper('route.article', array('row' => $article)) ?>"><?= translate('Read more') ?></a>
    <? endif; ?>
</article>