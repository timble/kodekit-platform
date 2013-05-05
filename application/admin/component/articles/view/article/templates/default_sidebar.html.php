<?php
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset>
    <legend><?= @text('Publish') ?></legend>
    <div>
        <label for="published"><?= @text('Published') ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="access"><?= @text('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $article->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="publish_on"><?= @text('Publish on') ?></label>
        <div class="controls-calendar">
            <?= @helper('behavior.calendar', array('date' => $article->publish_on, 'name' => 'publish_on')); ?>
        </div>
    </div>
    <div>
        <label for="unpublish_on"><?= @text('Unpublish on') ?></label>
        <div class="controls-calendar">
            <?= @helper('behavior.calendar', array('date' => $article->unpublish_on, 'name' => 'unpublish_on')); ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?= @text('Details') ?></legend>
    <div>
        <label for="created_by"><?= @text('Author') ?></label>
        <div>
            <?= @helper('com:users.listbox.users', array('autocomplete' => true, 'name' => 'created_by', 'value' => 'created_by', 'selected' => $article->id ? $article->created_by : @object('user')->getId())) ?>
        </div>
    </div>
    <div>
        <label for="created_on"><?= @text('Created on') ?></label>
        <div>
            <p class="help-block"><?= @helper('date.humanize', array('date' => $article->created_on)) ?></p>
        </div>
    </div>
</fieldset>

<fieldset class="categories group">
    <legend><?= @text('Category') ?></legend>
    <div>
        <?= @template('default_categories.html', array('categories' =>  @object('com:articles.model.categories')->sort('title')->table('articles')->getRowset(), 'article' => $article)) ?>
    </div>
</fieldset>

<fieldset>
    <legend><?= @text('Description') ?></legend>
    <div>
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
        <?= @helper('com:terms.listbox.terms', array('name' => 'terms[]', 'selected' => $article->getTerms()->terms_term_id, 'filter' => array('table' => 'articles'), 'attribs' => array('class' => 'select-terms', 'multiple' => 'multiple', 'style' => 'width:220px'))) ?>
    </fieldset>
<? endif ?>