<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" class="-koowa-form" id="category-form">
    <div class="form-body">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
		</div>

		<div class="form-content">
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
    	<fieldset class="form-horizontal">
    		<legend><?= @text( 'Publish' ); ?></legend>
    		<div class="control-group">
    		    <label class="control-label" for="enabled"><?= @text('Published') ?></label>
    		    <div class="controls">
    		        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $category->enabled)) ?>
    		    </div>
    		</div>
    		<div class="control-group">
    		    <label class="control-label" for="access"><?= @text('Access Level') ?></label>
    		    <div class="controls">
    		        <?= @helper('listbox.access', array('name' => 'access', 'state' => $category, 'deselect' => false)) ?>
    		    </div>
    		</div>
    	</fieldset>
        <fieldset class="categories group">
            <legend><?= @text('Parent') ?></legend>
            <div class="control-group">
                <?= @helper('com://admin/categories.template.helper.listbox.categories', array(
                'name'      => 'category_id',
                'selected'  => $category->parent_id,
                'attribs'   => array('id' => 'category_id', 'class' => 'required'),
                'prompt'    => '- None -',
                'max_depth' => 1
            )) ?>
            </div>
        </fieldset>
    </div>
</form>