<?
/**
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.validator') ?>

<!--
<script src="media://koowa/js/koowa.js" />
<style src="media://koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" class="-koowa-form" id="category-form">
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="table" value="<?= $state->table ?>" />
    
    <div class="main">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
		    <div class="slug">
		        <span class="add-on"><?= @text('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $category->slug ?>" />
		    </div>
		</div>

		<div class="scrollable">
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text( 'Description' ); ?></label>
				    <div class="controls">
				        <textarea rows="9" name="description"><?= $category->description; ?></textarea>
				    </div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Image' ); ?></legend>
				<div class="control-group">
				    <label class="control-label" for="image"><?= @text( 'Image' ); ?></label>
				    <div class="controls">
				        <?= @helper('image.listbox', array('name' => 'image')); ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

    <div class="sidebar">
    	<div class="scrollable">
	    	<fieldset class="form-horizontal">
	    		<legend><?= @text( 'Publish' ); ?></legend>
	    		<div class="control-group">
	    		    <label class="control-label" for="published"><?= @text('Published') ?></label>
	    		    <div class="controls">
	    		        <input type="checkbox" name="published" value="1" <?= $category->published ? 'checked="checked"' : '' ?> />
	    		    </div>
	    		</div>
	    		<div class="control-group">
	    		    <label class="control-label" for="access"><?= @text('Registered') ?></label>
	    		    <div class="controls">
	    		        <input type="checkbox" name="access" value="1" <?= $category->access ? 'checked="checked"' : '' ?> />
	    		    </div>
	    		</div>
	    	</fieldset>
	    	<? if($state->table == 'articles') : ?>
	        <fieldset class="categories group">
	            <legend><?= @text('Parent') ?></legend>
	            <div class="control-group">
	                <?= @helper('com://admin/categories.template.helper.listbox.categories', array(
	                'name'      => 'parent_id',
	                'selected'  => $category->parent_id,
	                'prompt'    => '- None -',
	                'max_depth' => 1,
	                'table'     => 'articles',
	                'parent'	=> '0'
	            )) ?>
	            </div>
	        </fieldset>
	        <? endif ?>
        </div>
    </div>
</form>