<?php
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

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

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="<?= @route('&id='.$menu->id)?>" method="post" class="-koowa-form">
	<div class="form-body">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $menu->title ?>" placeholder="<?= @text('Title') ?>" />
        </div>
		<div class="form-content">			
            <label for="name"><?= @text('Unique Name') ?>:</label>
            <input class="inputbox" type="text" name="name" size="30" maxlength="25" value="<?= $menu->slug ?>" />

            <label for="description"><?= @text('Description') ?>:</label>
            <textarea name="description" rows="3" placeholder="<?= @text('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>
			      
		    <? if(!$state->id) : ?>
		    <label for="module_title"><?= @text('Module Title') ?>:</label>
		    <input class="inputbox" type="text" name="module_title" id="module_title" size="30" value="" />
		    <? endif ?>
		</div>
    </div>
</form>
