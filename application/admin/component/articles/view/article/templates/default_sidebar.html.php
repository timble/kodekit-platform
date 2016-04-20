<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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
            <?= helper('date.datetime', array('entity' => $article, 'name' => 'publish_on')) ?>
        </div>
    </div>
    <div>
        <label for="unpublish_on"><?= translate('Unpublish on') ?></label>
        <div>
            <?= helper('date.datetime', array('entity' => $article, 'name' => 'unpublish_on')) ?>
        </div>
    </div>
</fieldset>

<div class="tabs tabs-horizontal">
    <div class="tab">
        <input type="radio" id="tab-1" name="tab-group-1" checked="">
        <label for="tab-1"><?= translate('Classifications') ?></label>
        <div class="content">
            <? if($article->isCategorizable()) : ?>
            <fieldset>
                <legend><?= translate('Category') ?></legend>
                <?= helper('com:categories.radiolist.categories', array(
                    'entity' => $article, 'uncategorised' => true
                )) ?>
            </fieldset>
            <? endif ?>
            <? if($article->isTaggable()) : ?>
                <fieldset>
                    <legend><?= translate('Tags') ?></legend>
                    <?= helper('com:tags.listbox.tags', array(
                        'name'     => 'tags[]',
                        'selected' => $article->getTags(),
                        'attribs'  => array('class' => 'select-tags', 'multiple' => 'multiple', 'style' => 'width:100%')
                    )) ?>
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
                    <?= import('com:articles/attachments/list.html', array(
                        'attachments'               => $article->getAttachments(),
                        'attachments_attachment_id' => $article->attachments_attachment_id
                    )) ?>
                <? endif ?>
                <?= import('com:articles/attachments/upload.html') ?>
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
