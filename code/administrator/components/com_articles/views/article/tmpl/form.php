<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
-->
<script>
    if(Form && Form.Validator) {
        Form.Validator.add('validate-unsigned', {
            errorMsg: Form.Validator.getMsg("required"),
            test: function(field){
                return field.get('value').toInt() >= 0;
            }
        });
    }
</script>

<?= @template('com://admin/default.view.form.toolbar') ?>

<? if($article->isTranslatable()) : ?>
    <ktml:module position="toolbar" content="append">
        <?= @helper('com://admin/languages.template.helper.listbox.languages') ?>
    </ktml:module>
<? endif ?>

<form action="" method="post" id="article-form" class="-koowa-form">
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="featured" value="0" />
    
    <div class="form-body">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $article->title ?>" placeholder="<?= @text('Title') ?>" />
        </div>
        <?= @service('com://admin/wysiwyg.controller.editor')->render(array('name' => 'text', 'text' => $article->text)) ?>
    </div>
    <div class="sidebar">        
        <div class="scrollable">
	        <fieldset class="form-horizontal">
	        	<legend><?= @text('Publish') ?></legend>
	            <div class="control-group">
	                <label class="control-label" for="published"><?= @text('Published') ?></label>
	                <div class="controls">
	                    <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
	                </div>
	            </div>
	            <div class="control-group">
	                <label class="control-label" for="featured"><?= @text('Featured') ?></label>
	                <div class="controls">
	                    <input type="checkbox" name="featured" value="1" <?= $article->featured ? 'checked="checked"' : '' ?> />
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
	            <div class="control-group">
	                <label class="control-label" for="slug"><?= @text('Slug') ?></label>
	                <div class="controls">
	                    <input type="text" name="slug" maxlength="255" value="<?= $article->slug ?>" placeholder="<?= @text('Slug') ?>" />
	                </div>
	            </div>
	        </fieldset>
	    
	        <fieldset class="form-horizontal">
	        	<legend><?= @text('Details') ?></legend>
	            <tbody>
	                <div class="control-group">
	                    <label class="control-label" for="created_by"><?= @text('Author') ?></label>
	                    <div class="controls">
	                        <?= @helper('com://admin/users.template.helper.listbox.users', array('autocomplete' => true, 'name' => 'created_by', 'value' => 'created_by', 'selected' => $article->id ? $article->created_by : @service('user')->getId())) ?>
	                    </div>
	                </div>
	                <div class="control-group">
	                    <label class="control-label" for="created_on"><?= @text('Created on') ?></label>
	                    <div class="controls">
	                    	<p class="help-block"><?= @helper('date.humanize', array('date' => $article->created_on)) ?></p>
	                    </div>
	                </div>
	            </tbody>
	        </fieldset>
	        
	        <fieldset class="categories group">
	            <legend><?= @text('Category') ?></legend>
	            <div class="control-group">
	            <?= @template('form_categories', array('categories' =>  @service('com://admin/articles.model.categories')->sort('title')->table('articles')->getRowset(), 'article' => $article)) ?>
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
    	                <?= @helper('com://admin/languages.template.helper.grid.status',
    	                    array('status' => $translation->status, 'original' => $translation->original, 'deleted' => $translation->deleted)) ?>
    	            <? endforeach ?>
    	        </fieldset>
	        <? endif ?>
        </div>
    </div>
</form>