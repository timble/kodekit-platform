<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://com_articles/css/article-form.css" />

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

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="article-form" class="-koowa-form -koowa-box">
    <div class="-koowa-box-vertical -koowa-box-flex1">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $article->title ?>" placeholder="<?= @text('Title') ?>" />
        </div>

        <?/*= @editor(array(
                'name' => 'text',
                'text' => $article->text,
                'width' => '100%',
                'height' => '300',
                'cols' => '60',
                'rows' => '20',
                'buttons' => true,
                'options' => array('theme' => 'simple', 'pagebreak', 'readmore')));
        //*/?>
        <?= @service('com://admin/editors.controller.editor')->name('text')->data($article->text)->display() ?>
    </div>
    <div id="sidebar" class="grid_3">        
        <fieldset class="form-horizontal">
        	<legend><?= @text('Publish') ?></legend>
            <div class="control-group">
                <label class="control-label" for="state"><?= @text('Published') ?></label>
                <div class="controls controls-radio">
                    <?= @helper('select.booleanlist', array('name' => 'state', 'selected' => $article->state)) ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="featured"><?= @text('Featured') ?></label>
                <div class="controls controls-radio">
                    <?= @helper('select.booleanlist', array('name' => 'featured', 'selected' => $article->featured)) ?>
                </div>
            </div>
            <div class="control-group">
        	    <label class="control-label" for="publish_up"><?= @text('Publish on') ?></label>
                <div class="controls controls-calendar">
                    <?= @helper('behavior.calendar', array('date' => $article->publish_up, 'name' => 'publish_up')); ?>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="publish_down"><?= @text('Unpublish on') ?></label>
                <div class="controls controls-calendar">
                    <?= @helper('behavior.calendar', array('date' => $article->publish_down, 'name' => 'publish_down')); ?>
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
                        <?= @helper('com://admin/users.template.helper.listbox.users', array('autocomplete' => true, 'name' => 'created_by', 'selected' => $article->id ? $article->created_by : $user->id)) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="access"><?= @text('Visibility') ?></label>
                    <div class="controls">
                        <?= @helper('listbox.access', array('selected' => $article->access, 'deselect' => false)) ?>
                    </div>
                </div>
                <div class="control-group">
                    <label class="control-label" for="created_on"><?= @text('Created on') ?></label>
                    <div class="controls controls-calendar">
                    	<?= @helper('behavior.calendar', array('date' => $article->created_on, 'name' => 'created_on')); ?>
                    </div>
                </div>
            </tbody>
        </fieldset>
        
        <fieldset class="categories group">
            <legend><?= @text('Category') ?></legend>
            <div class="control-group">
            <?= @template('form_categories', array('categories' =>  @service('com://admin/articles.model.categories')->getList(), 'article' => $article)) ?>
            </div>
        </fieldset>
        <fieldset>
    		<legend><?= @text('Description') ?></legend>
    		<div class="control-group">
                <textarea name="description" rows="5"><?= $article->description ?></textarea>
            </div>
        </fieldset>
    </div>
</form>