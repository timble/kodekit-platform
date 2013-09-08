<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" class="-koowa-form" id="category-form">
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="table" value="<?= $state->table ?>" />
    
    <div class="main">
		<div class="title">
			<input class="required" type="text" name="title" maxlength="255" value="<?= $category->title; ?>" placeholder="<?= translate( 'Title' ); ?>" />
		    <div class="slug">
		        <span class="add-on"><?= translate('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $category->slug ?>" />
		    </div>
		</div>

		<div class="scrollable">
			<fieldset>
				<legend><?= translate( 'Details' ); ?></legend>
				<div>
				    <label for=""><?= translate( 'Description' ); ?></label>
				    <div>
				        <textarea rows="9" name="description"><?= $category->description; ?></textarea>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>

    <div class="sidebar">
	    <?= import('default_sidebar.html'); ?>
    </div>
</form>