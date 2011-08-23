<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<style src="media://com_pages/css/menu-form.css" />

<script language="javascript" type="text/javascript">
<!--
	function submitbutton(pressbutton) {
		var form = document.adminForm;

		if (pressbutton == 'savemenu') {
			if ( form.menutype.value == '' ) {
				alert( '<?= JText::_( 'Please enter a menu name', true ); ?>' );
				form.menutype.focus();
				return;
			}
			var r = new RegExp("[\']", "i");
			if ( r.exec(form.menutype.value) ) {
				alert( '<?= JText::_( 'The menu name cannot contain a \'', true ); ?>' );
				form.menutype.focus();
				return;
			}
			<?php if ($this->isnew) : ?>
			if ( form.title.value == '' ) {
				alert( '<?= JText::_( 'Please enter a module name for your menu', true ); ?>' );
				form.title.focus();
				return;
			}
			<?php endif; ?>
			submitform( 'savemenu' );
		} else {
			submitform( pressbutton );
		}
	}
//-->
</script>
<form action="<?= @route('&id='.$menu->id)?>" method="post" name="adminForm">
<input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $menu->title ?>" size="50" maxlength="255" />

<table class="adminform">
	<tr>
		<td width="100">
			<label for="name">
				<strong><?= @text('Unique Name') ?>:</strong>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="name" size="30" maxlength="25" value="<?= $menu->name ?>" />
		</td>
	</tr>
	<tr>
		<td width="100" >
			<label for="description">
				<strong><?= @text('Description') ?>:</strong>
			</label>
		</td>
		<td>
			<textarea name="description" rows="3" placeholder="<?= @text('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>
		</td>
	</tr>

	<? if(!$state->id) : ?>
	<tr>
		<td width="100"  valign="top">
			<label for="module_title">
				<strong><?= @text('Module Title') ?>:</strong>
			</label>
		</td>
		<td>
			<input class="inputbox" type="text" name="module_title" id="module_title" size="30" value="" />
		</td>
	</tr>
	<? endif ?>
</table>
</form>