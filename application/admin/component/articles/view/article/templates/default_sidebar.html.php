<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<fieldset>
    <div>
        <label for="published"><?= translate('Published') ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="access"><?= translate('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $article->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="publish_on"><?= translate('Publish on') ?></label>
        <div>
            <?= helper('date.datetime', array('row' => $article, 'name' => 'publish_on')) ?>
        </div>
    </div>
    <div>
        <label for="unpublish_on"><?= translate('Unpublish on') ?></label>
        <div>
            <?= helper('date.datetime', array('row' => $article, 'name' => 'unpublish_on')) ?>
        </div>
    </div>
</fieldset>

<div class="tabs tabs-horizontal">
    <div class="tab">
        <input type="radio" id="tab-1" name="tab-group-1" checked="">
        <label for="tab-1"><?= translate('Classifications') ?></label>
        <div class="content">
            <fieldset>
                <legend><?= translate('Category') ?></legend>
                <?= helper('com:categories.radiolist.categories', array('row' => $article, 'uncategorised' => true)) ?>
            </fieldset>
            <? if($article->isTaggable()) : ?>
                <fieldset>
                    <legend><?= translate('Tags') ?></legend>
                    <?= helper('com:tags.listbox.tags', array('name' => 'tags[]', 'selected' => $article->getTags()->tags_tag_id, 'filter' => array('table' => 'articles'), 'attribs' => array('class' => 'select-tags', 'multiple' => 'multiple', 'style' => 'width:220px'))) ?>
                </fieldset>
            <? endif ?>
        </div>
    </div>
    <? if($article->isAttachable()) : ?>
    <div class="tab">
        <input type="radio" id="tab-3" name="tab-group-1">
        <label for="tab-3"><?= translate('Attachments') ?></label>
        <div class="content">
            <fieldset>
                <? if (!$article->isNew()) : ?>
                    <?= import('com:attachments.view.attachments.list.html', array('attachments' => $article->getAttachments(), 'attachments_attachment_id' => $article->attachments_attachment_id)) ?>
                <? endif ?>
                <?= import('com:attachments.view.attachments.upload.html') ?>
            </fieldset>
        </div>
    </div>
    <? endif ?>
</div>

<? if($article->isTranslatable()) : ?>
    <fieldset>
        <legend><?= translate('Translations') ?></legend>
        <? $translations = $article->getTranslations() ?>
        <? foreach($article->getLanguages() as $language) : ?>
            <?= $language->name.':' ?>
            <? $translation = $translations->find(array('iso_code' => $language->iso_code)) ?>
            <?= helper('com:languages.grid.status',
                array('status' => $translation->status, 'original' => $translation->original, 'deleted' => $translation->deleted)) ?>
        <? endforeach ?>
    </fieldset>
<? endif ?>
