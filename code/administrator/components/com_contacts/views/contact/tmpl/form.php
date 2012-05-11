<?php
/**
 * @version     $Id: form.php 2004 2011-06-26 16:32:54Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->
<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="contact-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $contact->id; ?>" />
	
	<div class="form-body">
		<div class="title">
		    <input class="required" type="text" name="title" maxlength="255" value="<?= $contact->name ?>" placeholder="<?= @text('Name') ?>" />
		</div>

		<div class="form-content">
			<fieldset class="form-horizontal">
				<legend><?= @text('Information'); ?></legend>
				<div class="control-group">
				    <label class="control-label" for="con_position"><?= @text( 'Position' ); ?></label>
				    <div class="controls">
				        <input type="text" name="con_position" maxlength="255" value="<?= $contact->con_position; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="email_to"><?= @text( 'E-mail' ); ?></label>
				    <div class="controls">
				        <input type="text" name="email_to" maxlength="255" value="<?= $contact->email_to; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="address"><?= @text( 'Street Address' ); ?></label>
				    <div class="controls">
				        <textarea name="address" rows="5"><?= $contact->address; ?></textarea>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="suburb"><?= @text( 'Town/Suburb' ); ?></label>
				    <div class="controls">
				        <input type="text" name="suburb" maxlength="100" value="<?= $contact->suburb;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="state"><?= @text( 'State/County' ); ?></label>
				    <div class="controls">
				        <input type="text" name="state" maxlength="100" value="<?= $contact->state;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="postcode"><?= @text( 'Postal Code/ZIP' ); ?></label>
				    <div class="controls">
				        <input type="text" name="postcode" maxlength="100" value="<?= $contact->postcode; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="country"><?= @text( 'Country' ); ?></label>
				    <div class="controls">
				        <input type="text" name="country" maxlength="100" value="<?= $contact->country;?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="telephone"><?= @text( 'Telephone' ); ?></label>
				    <div class="controls">
				        <input type="text" name="telephone" maxlength="255" value="<?= $contact->telephone; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="mobile"><?= @text( 'Mobile' ); ?></label>
				    <div class="controls">
				        <input type="text" name="mobile" maxlength="255" value="<?= $contact->mobile; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="fax"><?= @text( 'Fax' ); ?></label>
				    <div class="controls">
				        <input type="text" name="fax" maxlength="255" value="<?= $contact->fax; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="webpage"><?= @text( 'Webpage' ); ?></label>
				    <div class="controls">
				        <input type="text" name="webpage" maxlength="255" value="<?= $contact->webpage; ?>" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="misc"><?= @text( 'Miscellaneous Info' ); ?></label>
				    <div class="controls">
				        <textarea name="misc" rows="5"><?= $contact->misc; ?></textarea>
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for="image"><?= @text( 'Image' ); ?></label>
				    <div class="controls">
				        <?= @helper('image.listbox', array('name' => 'image')); ?>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

	<div class="sidebar" style="width: 430px;">
		<fieldset class="form-horizontal">
			<legend><?= @text('Publish'); ?></legend>
			<div class="control-group">
			    <label class="control-label" for="enabled"><?= @text( 'Published' ); ?></label>
			    <div class="controls">
			        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $contact->enabled)); ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="catid"><?= @text( 'Category' ); ?></label>
			    <div class="controls">
			        <?= @helper('com://admin/categories.template.helper.listbox.categories', array(
			            'name' => 'catid',
			            'text' => 'title',
			            'filter' => array('section' => 'com_contacts_contacts'),
			            'selected' => $contact->category,
			        )); ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="slug"><?= @text( 'Alias' ); ?></label>
			    <div class="controls">
			        <input type="text" name="slug" maxlength="255" value="<?= $contact->slug; ?>" />
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="user_id"><?= @text( 'Linked to User' ); ?></label>
			    <div class="controls">
			        <?= @helper('com://admin/users.template.helper.listbox.users', array('autocomplete' => true, 'text' => 'name', 'name' => 'user_id')) ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="ordering"><?= @text( 'Ordering' ); ?></label>
			    <div class="controls">
			        <?= @helper('listbox.ordering'); ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for="access"><?= @text( 'Access' ); ?></label>
			    <div class="controls">
			        <?= @helper('com://admin/default.template.helper.listbox.access', array('name' => 'access', 'selected' => $contact->access, 'deselect' => false)); ?>
			    </div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend><?= @text('Parameters'); ?></legend>

			<?= @helper('tabs.startPane'); ?>
			<?= @helper('tabs.startPanel', array('title' => @text('Contact Parameters'))); ?>
			<?= $contact->params->render(); ?>
			<?= @helper('tabs.endPanel', array()); ?>
			<?= @helper('tabs.startPanel', array('title' => @text('E-mail Parameters'))); ?>
			<?= $contact->params->render('params', 'email'); ?>
			<?= @helper('tabs.endPanel'); ?>
			<?= @helper('tabs.endPane'); ?>

		</fieldset>
	</div>
	
</form>