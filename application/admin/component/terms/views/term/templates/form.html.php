<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright   Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

<?= @helper('behavior.tooltip'); ?>

<style src="media://default/css/form.css" />
<style src="media://terms/css/admin.css" />

<form action="" method="post" class="-koowa-form">
	<div style="width:100%; float: left" id="mainform">
		<fieldset>
			<legend><?= @text('Details'); ?></legend>
			<label for="title" class="mainlabel"><?= @text('Title'); ?></label>
			<input id="title" type="text" name="title" value="<?= $term->title; ?>" />
			<br />
			<label for="slug" class="mainlabel"><?= @text('Slug'); ?></label>
			<input id="slug" type="text" name="slug" value="<?= $term->slug; ?>" />
		</fieldset>
		<fieldset>
			<legend><?= @text('Description'); ?></legend>
			<?= @editor(array('row' => $term, 'height' => 50, 'options' => array('theme' => 'simple'))) ?>
		</fieldset>
	</div>
</form>