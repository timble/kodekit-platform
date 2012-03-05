<?php
/**
 * @version     $Id$
 * @category    Nooku
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

<form action="" method="post" class="-koowa-form -koowa-box" id="category-form">
    <div class="-koowa-box-vertical -koowa-box-flex1">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
		</div>

		<div class="-koowa-box-flex1 -koowa-box-scroll" style="padding: 20px;">
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
				<div class="control-group">
				    <label class="control-label" for="image_position"><?= @text( 'Image Position' ); ?></label>
				    <div class="controls">
				        <?=  @helper('image.position'); ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

    <div id="sidebar" style="width: 300px;">
    	<fieldset class="form-horizontal">
    		<legend><?= @text( 'Publish' ); ?></legend>
    		<div class="control-group">
    		    <label class="control-label" for="enabled"><?= @text('Published') ?></label>
    		    <div class="controls controls-radio">
    		        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $category->enabled)) ?>
    		    </div>
    		</div>
    		<? $section = $category->id ? $category->section_id : $state->section ?>
    		<? if(substr($section, 0, 3) != 'com' || $section =='com_content') : ?>
    		<div class="control-group">
    		    <label class="control-label" for="section_id"><?= @text('Section') ?></label>
    		    <div class="controls">
    		        <input type="hidden" name="old_parent" value="<?= $category->section_id ?>" />
    		        <?= @helper('listbox.sections', array(
    		        	'name' => 'section_id', 
    		        	'selected' => $category->section_id, 
    		        	'attribs' => array('id' => 'section_id', 'class' => 'required'),
    		        	'deselect' => false,
    		        	'uncategorised' => false
    		        )) ?>
    		    </div>
    		</div>
    		<? else : ?>
    		    <input type="hidden" name="section_id" value="<?= $section ?>" />
    		<? endif ?>
    		<div class="control-group">
    		    <label class="control-label" for="access"><?= @text('Access Level') ?></label>
    		    <div class="controls">
    		        <?= @helper('listbox.access', array('name' => 'access', 'state' => $category, 'deselect' => false)) ?>
    		    </div>
    		</div>
    	</fieldset>
    </div>
</form>