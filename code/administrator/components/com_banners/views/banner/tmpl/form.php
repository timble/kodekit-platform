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

defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip');?>
  
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

function submitbutton(pressbutton) {
	var form = document.adminForm;
	if (pressbutton == 'cancel') {
		submitform( pressbutton );
		return;
	}
	
	if (form.name.value == "") {
		alert( "<?= @text( 'You must provide a banner name.', true ); ?>" );
	/*} else if (!getSelectedValue('adminForm','imageurl')) {
		alert( "<?= @text( 'Please select an image.', true ); ?>" );*/
	/*} else if (form.clickurl.value == "") {
		alert( "<?= @text( 'Please fill in the URL for the banner.', true ); ?>" );*/
	} else if ( getSelectedValue('adminForm','catid') == 0 ) {
		alert( "<?= @text( 'Please select a category.', true ); ?>" );
	} else {
		submitform( pressbutton );
	}
}
</script>

</script>

<form action="<?=@route('id='.$banner->id)?>" method="post" name="adminForm">

    <div class="col100">
        <fieldset class="adminform">
            <legend><?=@text( 'Details' ); ?></legend>

            <table class="admintable">
            <tbody>
                <tr>
                    <td width="20%" class="key">
                        <label for="name">
                            <?=@text( 'Name' ); ?>:
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" name="name" id="name" size="50" value="<?= $banner->name;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="key">
                        <label for="alias">
                            <?=@text( 'Alias' ); ?>:
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" name="alias" id="alias" size="50" value="<?= $banner->slug;?>" />
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?=@text( 'Show Banner' ); ?>:
                    </td>
                    <td>
                        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $banner->enabled)); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <?=@text( 'Sticky' ); ?>:
                    </td>
                    <td>
                        <?= @helper('select.booleanlist', array('name' => 'sticky', 'selected' => $banner->sticky)); ?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="ordering">
                            <?=@text( 'Ordering' ); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="ordering" id="ordering" 
                        size="6" value="<?= $banner->ordering;?>" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" align="right" class="key">
                        <label for="catid">
                            <?=@text( 'Category' ); ?>:
                        </label>
                    </td>
                    <td>
                        <?=@helper('listbox.categories', array('name' => 'catid', 'selected' => $banner->catid ))?>
                    </td>
                </tr>
                <tr>
                    <td class="key">
                        <label for="clickurl">
                            <?=@text( 'Click URL' ); ?>:
                        </label>
                    </td>
                    <td>
                        <input class="inputbox" type="text" name="clickurl" id="clickurl" 
                        size="100" maxlength="200" value="<?= $banner->clickurl;?>" />
                    </td>
                </tr>
                <tr >
                    <td valign="top" align="right" class="key">
                        <?=@text( 'Clicks' ); ?>:
                    </td>
                    <td colspan="2">
                        <span id="clicks_label"><?= $banner->clicks;?></span>
                        <input id="reset_hits" name="reset_hits" type="button" class="button" value="<?=@text( 'Reset Clicks' ); ?>" />
                        <input type="hidden" id="clicks_field" name="clicks" value="<?= $banner->clicks; ?>" />
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <label for="custombannercode">
                            <?=@text( 'Custom banner code' ); ?>:
                        </label>
                    </td>
                    <td>
                        <textarea class="inputbox" cols="70" rows="8" name="custombannercode" id="custombannercode"><?= $banner->custombannercode;?></textarea>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <label for="description">
                            <?=@text( 'Description/Notes' ); ?>:
                        </label>
                    </td>
                    <td>
                        <textarea class="inputbox" cols="70" rows="3" name="description" id="description"><?= $banner->description;?></textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <label for="imageurl">
                            <?=@text( 'Banner Image Selector' ); ?>:
                        </label>
                    </td>
                    <td >
                        <?= @helper('listbox.banner_names', array(
                        	'name'      => 'imageurl',  
                            'preview'   => false, 
                            'width'     => $banner->params->get('width'),
                            'height'    => $banner->params->get('height')
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <?=@text( 'Banner Image' ); ?>:
                    </td>
                    <td valign="top">
                        <?= @helper('listbox.banner_preview', array(
                        	'name'      => 'imageurl', 
                            'selected'  => $banner->imageurl
                        )); ?>
                    </td>
                </tr>
                <tr>
                    <td valign="top" class="key">
                        <label for="tags">
                            <?=@text( 'Tags' ); ?>:
                        </label>
                    </td>
                    <td>
                        <textarea class="inputbox" cols="70" rows="3" name="tags" id="tags"><?= $banner->tags;?></textarea>
                    </td>
                </tr>
            </tbody>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>
</form>
