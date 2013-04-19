<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset class="form-horizontal">
    <legend><?= @text('Publish') ?></legend>
    <div class="control-group">
        <label class="control-label" for="published"><?= @text('Published') ?></label>
        <div class="controls">
            <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="access"><?= @text('Registered') ?></label>
        <div class="controls">
            <input type="checkbox" name="access" value="1" <?= $article->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="publish_on"><?= @text('Publish on') ?></label>
        <div class="controls controls-calendar">
            <?= @helper('behavior.calendar', array('date' => $article->publish_on, 'name' => 'publish_on')); ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="unpublish_on"><?= @text('Unpublish on') ?></label>
        <div class="controls controls-calendar">
            <?= @helper('behavior.calendar', array('date' => $article->unpublish_on, 'name' => 'unpublish_on')); ?>
        </div>
    </div>
</fieldset>

<fieldset class="form-horizontal">
    <legend><?= @text('Details') ?></legend>
    <div class="control-group">
        <label class="control-label" for="created_by"><?= @text('Author') ?></label>
        <div class="controls">
            <?= @helper('com:users.listbox.users', array('autocomplete' => true, 'name' => 'created_by', 'value' => 'created_by', 'selected' => $article->id ? $article->created_by : @service('user')->getId())) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="created_on"><?= @text('Created on') ?></label>
        <div class="controls">
            <p class="help-block"><?= @helper('date.humanize', array('date' => $article->created_on)) ?></p>
        </div>
    </div>
</fieldset>

<fieldset class="categories group">
    <legend><?= @text('Category') ?></legend>
    <div class="control-group">
        <?= @template('default_categories.html', array('categories' =>  @service('com:articles.model.categories')->sort('title')->table('articles')->getRowset(), 'article' => $article)) ?>
    </div>
</fieldset>
<fieldset>
    <legend><?= @text('Description') ?></legend>
    <div class="control-group">
        <textarea name="description" rows="5"><?= $article->description ?></textarea>
    </div>
</fieldset>

<? if($article->isTranslatable()) : ?>
    <fieldset>
        <legend><?= @text('Translations') ?></legend>
        <? $translations = $article->getTranslations() ?>
        <? foreach($article->getLanguages() as $language) : ?>
            <?= $language->name.':' ?>
            <? $translation = $translations->find(array('iso_code' => $language->iso_code)) ?>
            <?= @helper('com:languages.grid.status',
                array('status' => $translation->status, 'original' => $translation->original, 'deleted' => $translation->deleted)) ?>
        <? endforeach ?>
    </fieldset>
<? endif ?>

<? if($article->isAttachable()) : ?>
    <fieldset>
        <legend><?= @text('Attachments') ?></legend>
        <? if (!$article->isNew()) : ?>
            <?= @template('com:attachments.view.attachments.list.html', array('attachments' => $article->getAttachments(), 'assignable' => true)) ?>
        <? endif ?>
        <?= @template('com:attachments.view.attachments.upload.html') ?>
    </fieldset>
<? endif ?>

<? if($article->isTaggable()) : ?>
    <fieldset>
        <legend><?= @text('Tags') ?></legend>
        <div class="control-group">
            <label class="control-label" for="created_by"><?= @text('Tags') ?></label>
            <div class="controls">
                <?= @helper('com:terms.listbox.terms', array('name' => 'terms[]', 'selected' => $article->getTerms()->terms_term_id, 'filter' => array('table' => 'articles'), 'attribs' => array('class' => 'select-terms', 'multiple' => 'multiple', 'style' => 'width:220px'))) ?>
            </div>
        </div>
    </fieldset>
<? endif ?>