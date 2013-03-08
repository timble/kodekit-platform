<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
?>

<?= @helper('behavior.mootools'); ?>
<?= @helper('behavior.keepalive'); ?>

<!--
<script src="media://koowa/js/koowa.js"/>
-->

<div id="toolbar-box">
    <?= @helper('com://admin/base.template.helper.toolbar.render', array('toolbar' => $toolbar));?>
</div>

<form method="post" action="" class="-koowa-form form-horizontal">
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="access" value="0" />
    
    <fieldset>
    	<legend><?= @text('Article'); ?></legend>
    	<div class="control-group">
    	    <label class="control-label" for="title"><?= @text('Title') ?></label>
    	    <div class="controls">
    	        <input class="inputbox" type="text" id="title" name="title" size="50" maxlength="100" value="<? echo @escape($article->title); ?>"/>
    	    </div>
    	</div>
        <?= @service('com://admin/wysiwyg.controller.editor')->render(array('name' => 'text', 'text' => $article->text)) ?>
    </fieldset>
    <fieldset>
        <legend><?= @text('Publishing'); ?></legend>
        <div class="control-group">
            <label class="control-label" for="title"><?= @text('Published'); ?></label>
            <div class="controls">
                <input type="checkbox" name="published" value="1" <?= $article->published ? 'checked="checked"' : '' ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="access"><?= @text('Registered'); ?></label>
            <div class="controls">
                <input type="checkbox" name="access" value="1" <?= $article->access ? 'checked="checked"' : '' ?> />
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="publish_on"><?= @text('Publish on'); ?></label>
            <div class="controls">
                <?= @helper('behavior.calendar', array('date' => $article->publish_on, 'name' => 'publish_on')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="unpublish_on"><?= @text('Unpublish on'); ?></label>
            <div class="controls">
                <?= @helper('behavior.calendar', array('date' => $article->publishun_on, 'name' => 'publishun_on')); ?>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="categories_category_id"><?= @text('Category'); ?></label>
            <div class="controls">
                <?= @template('com://admin/articles.view.article.form_categories.html', array('categories' =>  @service('com://site/articles.model.categories')->sort('title')->table('articles')->getRowset(), 'article' => $article)) ?>
            </div>
        </div>
    </fieldset>
</form>