<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.keepalive'); ?>
<?= @helper('behavior.validator'); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="section-form" class="-koowa-form -koowa-box">
	<input type="hidden" name="oldtitle" value="<?= $section->title ; ?>" />

	<div class="-koowa-box-vertical -koowa-box-flex1">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $section->title; ?>" placeholder="<?= @text( 'Title' ); ?>" />
		</div>

		<div class="-koowa-box-flex1 -koowa-box-scroll" style="padding: 20px;">
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text( 'Description' ); ?></label>
				    <div class="controls">
				        <textarea rows="9" name="description"><?= $section->description; ?></textarea>
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
			    <label class="control-label" for="enabled"><?= @text( 'Published' ); ?></label>
			    <div class="controls">
			        <?= @helper('listbox.published', array('name' => 'enabled', 'state' => $section, 'deselect' => false)); ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="slug"><?= @text( 'Slug' ); ?></label>
			    <div class="controls">
			        <input type="text" name="slug" maxlength="255" value="<?= $section->slug; ?>" title="<?= @text( 'ALIASTIP' ); ?>" placeholder="<?= @text( 'Slug' ); ?>" />
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="access"><?= @text( 'Access Level' ); ?></label>
			    <div class="controls">
			        <?= @helper('listbox.access', array('name' => 'access', 'state' => $section, 'deselect' => false)); ?>
			    </div>
			</div>
		</fieldset>
	</div>
</form>