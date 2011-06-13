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
	$('unlimited').addEvent('click', function() {
		if($(this).getValue()){
			$('imptotal').set('value','');
		}
	});
    $('imptotal').addEvent('focus', function() {
        $('unlimited').set('checked',false);
    });
    $('reset_hits').addEvent('click', function() {
        $('clicks_label').setHTML(0);
        $('clicks_field').set('value',0);
    });
});
</script>

<form action="<?= @route('id='.$banner->id) ?>" method="post" id="banner-form" class="-koowa-form">
    <div class="grid_8">
        <div class="panel title group">
			<input class="inputbox required" type="text" name="name" id="title" size="40" maxlength="255" value="<?= @escape($banner->name) ?>" placeholder="<?= @text('Name') ?>" />
		
			<label for="alias">
				<?= @text( 'Alias' ) ?>
				<input class="inputbox" type="text" name="alias" id="alias" size="40" maxlength="255" value="<?= @escape($banner->slug) ?>" title="<?= @text('ALIASTIP') ?>" placeholder="<?= @text('Alias') ?>" />
			</label>
		</div>
		
		<div class="panel">
		    <h3><?= @text('Banner') ?></h3>
		    <table class="admintable">
		        <tbody>
		            <tr>
		                <td valign="top" class="key">
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
		        </tbody>
		    </table>
		</div>
    </div>
    <div class="grid_4">
        <div class="panel">
        	<h3><?= @text( 'Publish' ); ?></h3>
        	<table class="admintable">
        	    <tbody>
        	        <tr>
        	            <td class="key">
        	                <?= @text( 'Published' ) ?>:
        	            </td>
        	            <td>
        	                <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $banner->enabled)) ?>
        	            </td>
        	        </tr>
        	        <tr>
        	            <td class="key">
        	                <?= @text( 'Sticky' ) ?>:
        	            </td>
        	            <td>
        	                <?= @helper('select.booleanlist', array('name' => 'sticky', 'selected' => $banner->sticky)) ?>
        	            </td>
        	        </tr>
        	        <tr>
        	            <td class="key">
        	                <label for="ordering">
        	                    <?= @text( 'Ordering' ) ?>:
        	                </label>
        	            </td>
        	            <td>
        	                <div id="orderable">
        	                   <? if($banner->id): ?>
                                    <?= @helper('admin::com.categories.template.helper.listbox.order',
                                    array(
                                        'package' => 'banners', 
                                        'filter' => array(
                                        'category' => $banner->catid 
                                    ))); ?>
                                <? endif ?>
        	                </div>
        	            </td>
        	        </tr>
        	        <tr>
        	            <td valign="top" align="right" class="key">
        	                <label for="catid">
        	                    <?= @text( 'Category' ) ?>:
        	                </label>
        	            </td>
        	            <td>
        	                <input type="hidden" name="old_parent" value="<?= $banner->catid ?>" />
        	                <?= @helper('listbox.categories', array('name' => 'catid', 'attribs' => array('id' => 'catid', 'class' => 'inputbox required',
        	                 'onchange' => "var url = '"
                                        	.@route('&id=&layout=form_orderable&tmpl=component&format=ajax')
                                        	."&category='+$('catid').value;
                                                       new Ajax(url , {method: 'get',update: $('orderable')}).request();"
        	                ), 'selected' => $banner->catid )) ?>
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
        	            <td valign="top" align="right" class="key">
        	                <?= @text('Clicks') ?>:
        	            </td>
        	            <td colspan="2">
        	                <span id="clicks_label"><?= $banner->clicks ?></span>
        	                <input id="reset_hits" name="reset_hits" type="button" class="button" value="<?= @text('Reset Clicks') ?>" />
        	                <input type="hidden" id="clicks_field" name="clicks" value="<?= $banner->clicks ?>" />
        	            </td>
        	        </tr>
        	    </tbody>
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