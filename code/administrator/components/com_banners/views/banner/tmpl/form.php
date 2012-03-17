<?php
/**
 * @version     $Id$
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

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<script>
window.addEvent('domready', function(){
    $('reset_hits').addEvent('click', function() {
        $('hits_label').setHTML(0);
        $('hits_field').set('value',0);
    });
});
</script>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="banner-form" class="-koowa-form">
    <div class="editor-container">
        <div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= @escape($banner->title) ?>" placeholder="<?= @text('Name') ?>" />
		</div>
		<div class="editor">
			<fieldset class="form-horizontal">
			    <legend><?= @text('Banner') ?></legend>
			    <div class="control-group">
			        <label class="control-label" for="imageurl"><?= @text('Banner Image Selector') ?></label>
			        <div class="controls controls-radio">
			            <?= @helper('listbox.banner_names', array(
			            	'name'      => 'imageurl',
			            	'attribs'   => array(
			            	    'class' => 'required'
			            	),  
			                'preview'   => false, 
			                'width'     => $banner->params->get('width'),
			                'height'    => $banner->params->get('height')
			            )) ?>
			        </div>
			    </div>
			    <div class="control-group">
			        <label class="control-label"><?= @text( 'Banner Image' ) ?></label>
			        <div class="controls controls-radio">
			            <?= @helper('listbox.banner_preview', array(
			            	'name'      => 'imageurl', 
			                'selected'  => $banner->imageurl
			            )) ?>
			        </div>
			    </div>
			    <div class="control-group">
			        <label class="control-label" for="clickurl"><?= @text('Click URL') ?></label>
			        <div class="controls">
			            <input class="required validate-url" type="text" name="clickurl" maxlength="200" value="<?= $banner->clickurl ?>" />
			            <p class="help-block"><?= @text('Enter the full URL to the page that will open when the Banner is clicked upon') ?></p>
			        </div>
			    </div>
			</fieldset>
			
			<fieldset>
				<legend><?= @text('Custom banner code') ?></legend>
			    <div class="control-group">
			        <textarea rows="6" name="custombannercode" placeholder="<?= @text('Paste in your own custom banner code here&hellip;') ?>"><?= @escape($banner->custombannercode) ?></textarea>
			    </div>
			</fieldset>
		</div>
	</div>
	
	<div class="sidebar">
		<fieldset class="form-horizontal">
			<legend><?= @text('Publish') ?></legend>
        	<div class="control-group">
        	    <label class="control-label" for="state"><?= @text('Published') ?></label>
        	    <div class="controls controls-radio">
        	        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $banner->enabled)) ?>
        	    </div>
        	</div>
        	<div class="control-group">
        	    <label class="control-label" for="sticky"><?= @text('Sticky') ?></label>
        	    <div class="controls controls-radio">
        	        <?= @helper('select.booleanlist', array('name' => 'sticky', 'selected' => $banner->sticky)) ?>
        	    </div>
        	</div>
        	<div class="control-group">
        	    <label class="control-label" for="catid"><?= @text('Category') ?></label>
        	    <div class="controls">
        	        <?= @helper('listbox.category', array('name' => 'catid', 'selected' => $banner->catid, 'attribs' => array('id' => 'catid', 'class' => 'required'))) ?>
        	    </div>
        	</div>
        	<div class="control-group">
        	    <label class="control-label" for="reset_hits"><?= @text('Hits') ?></label>
        	    <div class="controls controls-radio">
        	        <span><?= $banner->hits ?></span>
        	        <input name="reset_hits" type="button" class="button" value="<?= @text('Reset Hits') ?>" />
        	        <input type="hidden" name="hits" value="<?= $banner->hits ?>" />
        	    </div>
        	</div>
        	<div class="control-group">
        	    <label class="control-label" for="slug"><?= @text('Slug') ?></label>
        	    <div class="controls controls-radio">
        	        <input type="text" name="slug" maxlength="255" value="<?= @escape($banner->slug) ?>" title="<?= @text('ALIASTIP') ?>" placeholder="<?= @text('Slug') ?>" />
        	    </div>
        	</div>
        </fieldset>
        
        <fieldset>
        	<legend><?= @text('Notes') ?></legend>
            <div class="control-group">
                <textarea rows="6" name="description" placeholder="<?= @text('Enter your notes in here&hellip;') ?>"><?= @escape($banner->description) ?></textarea>
            </div>
        </fieldset>
        
        <? /* @TODO consider using com.terms for this */ ?>
        <fieldset>
        	<legend><?= @text('Tags') ?></legend>
            <div class="control-group">
                <textarea rows="6" name="tags" placeholder="<?= @text('Enter comma separated tags here&hellip;') ?>"><?= @escape($banner->tags) ?></textarea>
            </div>
        </fieldset>
    </div>
</form>