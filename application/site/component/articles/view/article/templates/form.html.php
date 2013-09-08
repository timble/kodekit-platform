<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<title><?= !$article->isNew() ? $article->title : 'New Article' ?></title>

<?= helper('behavior.mootools'); ?>
<?= helper('behavior.keepalive'); ?>

<? if (object('component')->getController()->canEdit()) : ?>
    <?= helper('behavior.inline_editing'); ?>
<? endif;?>

<!--
<script src="assets://js/koowa.js"/>
-->

<div class="btn-toolbar">
    <ktml:toolbar type="actionbar">
</div>

<article <?= !$article->published ? 'class="article-unpublished"' : '' ?>>
    <div class="page-header">
        <h1 id="title" contenteditable="<?= object('component')->getController()->canEdit() ? 'true':'false';?>"><?= $article->title ?></h1>
        <?= helper('date.timestamp', array('row' => $article, 'show_modify_date' => false)); ?>
        <? if (!$article->published) : ?>
            <span class="label label-info"><?= translate('Unpublished') ?></span>
        <? endif ?>
        <? if ($article->access) : ?>
            <span class="label label-important"><?= translate('Registered') ?></span>
        <? endif ?>
    </div>

    <? if($article->thumbnail): ?>
        <img class="thumbnail" src="<?= $article->thumbnail ?>" align="right" style="margin:0 0 20px 20px;" />
    <? endif; ?>

    <? if($article->fulltext) : ?>
        <div id="introtext" class="article_introtext" contenteditable="<?= object('component')->getController()->canEdit() ? 'true':'false';?>">
            <?= $article->introtext ?>
        </div>
    <? else : ?>
        <div id="introtext" contenteditable="<?= object('component')->getController()->canEdit() ? 'true':'false';?>" >
            <?= $article->introtext ?>
        </div>
    <? endif ?>

    <div id="fulltext" contenteditable="<?= object('component')->getController()->canEdit() ? 'true':'false';?>">
        <?= $article->fulltext ?>
    </div>

    <?= import('com:tags.view.tags.default.html') ?>
    <?= import('com:attachments.view.attachments.default.html', array('attachments' => $attachments, 'exclude' => array($article->image))) ?>
</article>


<form method="post" action="" class="-koowa-form form-horizontal">
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="access" value="0" />

    <fieldset>
        <legend><?= translate('Publishing'); ?></legend>
        <div class="control-group">
            <label class="control-label" for="title"><?= translate('Published'); ?></label>
            <div class="controls">
                <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="access"><?= translate('Registered'); ?></label>
            <div class="controls">
                <input type="checkbox" name="access" value="1" <?= $article->access ? 'checked="checked"' : '' ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="publish_on"><?= translate('Publish on'); ?></label>
            <div class="controls">
                <input type="datetime-local" name="publish_on" value="<?= $article->publish_on ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="unpublish_on"><?= translate('Unpublish on'); ?></label>
            <div class="controls">
                <input type="datetime-local" name="unpublish_on" value="<?= $article->unpublish_on ?>" />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="categories_category_id"><?= translate('Category'); ?></label>
            <div class="controls">
                <?= helper('com:categories.radiolist.categories', array('row' =>  $article, 'uncategorised' => 'true')) ?>
            </div>
        </div>
    </fieldset>
</form>