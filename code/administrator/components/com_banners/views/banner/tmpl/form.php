<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
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

<form action="" method="post" id="banner-form" class="-koowa-form">
    <div class="grid_8">
        <div class="panel title group">
			<input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?= @escape($banner->title) ?>" placeholder="<?= @text('Name') ?>" />
		
			<label for="slug">
				<?= @text( 'Slug' ) ?>
				<input class="inputbox" type="text" name="slug" id="slug" size="40" maxlength="255" value="<?= @escape($banner->slug) ?>" title="<?= @text('ALIASTIP') ?>" placeholder="<?= @text('Slug') ?>" />
			</label>
		</div>
		
		<div class="panel">
		    <h3><?= @text('Banner') ?></h3>
		    <table class="admintable">
            <tr>
                <td class="key">
                    <label for="imageurl">
                        <?= @text( 'Banner Image Selector' ) ?>:
                    </label>
                </td>
                <td>
                    <?= @helper('listbox.banner_names', array(
                    	'name'      => 'imageurl',
                    	'attribs'   => array(
                    	    'class' => 'inputbox required'
                    	),  
                        'preview'   => false, 
                        'width'     => $banner->params->get('width'),
                        'height'    => $banner->params->get('height')
                    )) ?>
                </td>
            </tr>
            <tr>
                <td valign="top" class="key">
                    <?= @text( 'Banner Image' ) ?>:
                </td>
                <td valign="top">
                    <?= @helper('listbox.banner_preview', array(
                    	'name'      => 'imageurl', 
                        'selected'  => $banner->imageurl
                    )) ?>
                </td>
            </tr>
		    </table>
		</div>
    </div>
    <div class="grid_4">
        <div class="panel">
        	<h3><?= @text( 'Publish' ); ?></h3>
        	<table class="admintable">
	        <tr>
	            <td class="key">
	                <label for="enabled">
	                    <?= @text( 'Published' ) ?>:
	                </label>
	            </td>
	            <td>
	                <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $banner->enabled)) ?>
	            </td>
	        </tr>
	        <tr>
	            <td class="key">
	                <label for="sticky">
	                    <?= @text( 'Sticky' ) ?>:
	                </label>
	            </td>
	            <td>
	                <?= @helper('select.booleanlist', array('name' => 'sticky', 'selected' => $banner->sticky)) ?>
	            </td>
	        </tr>
	        <tr>
	            <td class="key">
	                <label for="catid">
	                    <?= @text( 'Category' ) ?>:
	                </label>
	            </td>
	            <td>
	                <?= @helper('listbox.category', array('name' => 'catid', 'selected' => $banner->catid, 'attribs' => array('id' => 'catid', 'class' => 'required'))) ?>
	            </td>
	        </tr>
	        <tr>
	            <td class="key">
	                <label for="clickurl">
	                    <?= @text( 'Click URL' ) ?>:
	                </label>
	            </td>
	            <td>
	                <input class="inputbox required validate-url" type="text" name="clickurl" id="clickurl" 
	                size="100" maxlength="200" style="box-sizing: border-box; width: 100%" value="<?= $banner->clickurl ?>" />
	            </td>
	        </tr>
	        <tr >
	            <td class="key">
	                <?= @text('Hits') ?>:
	            </td>
	            <td colspan="2">
	                <span id="hits_label"><?= $banner->hits ?></span>
	                <input id="reset_hits" name="reset_hits" type="button" class="button" value="<?= @text('Reset Hits') ?>" />
	                <input type="hidden" id="hits_field" name="hits" value="<?= $banner->hits ?>" />
	            </td>
	        </tr>
        	</table>
        </div>
        
        <div class="panel">
            <h3><?= @text('Description/Notes') ?></h3>
            <textarea class="inputbox" style="box-sizing: border-box; margin: 0; resize: vertical; width: 100%" cols="70" rows="6" name="description" id="description" placeholder="<?= @text('Enter your description and notes in here&hellip;') ?>"><?= @escape($banner->description) ?></textarea>
        </div>
        
        <div class="panel">
            <h3><?= @text('Custom banner code') ?></h3>
            <textarea class="inputbox" style="box-sizing: border-box; margin: 0; resize: vertical; width: 100%" cols="70" rows="6" name="custombannercode" id="custombannercode" placeholder="<?= @text('Paste in your own custom banner code here&hellip;') ?>"><?= @escape($banner->custombannercode) ?></textarea>
        </div>
        
        <? /* @TODO consider using com.terms for this */ ?>
        <div class="panel">
            <h3><?= @text('Tags') ?></h3>
            <textarea class="inputbox" style="box-sizing: border-box; margin: 0; resize: vertical; width: 100%" cols="70" rows="6" name="tags" id="tags" placeholder="<?= @text('Enter comma separated tags here&hellip;') ?>"><?= @escape($banner->tags) ?></textarea>
        </div>
    </div>
</form>