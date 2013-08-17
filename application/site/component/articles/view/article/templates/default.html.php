<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>
<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <header>
	    <? if (@object('component')->getController()->canEdit()) : ?>
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
	</header>

    <?= @helper('com:attachments.image.thumbnail', array('row' => $article)) ?>

    <? if($article->fulltext) : ?>
    <div class="article__introtext">
        <?= $article->introtext ?>
    </div>
    <? else : ?>
    <?= $article->introtext ?>
    <? endif ?>

    <?= $article->fulltext ?>
    
    <?= @template('com:tags.view.tags.default.html') ?>
    <?= @template('com:attachments.view.attachments.default.html', array('attachments' => $attachments, 'exclude' => array($article->attachments_attachment_id))) ?>
</article>