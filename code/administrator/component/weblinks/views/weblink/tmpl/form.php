<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<?= @helper('behavior.validator'); ?>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->
<form action="" method="post" id="weblink-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $weblink->id ?>" />
	<input type="hidden" name="published" value="0" />
	
	<div class="main">
		<div class="title">
		    <input class="required" type="text" name="title" maxlength="255" value="<?= $weblink->title ?>" placeholder="<?= @text('Title') ?>" />
		    <div class="slug">
		        <span class="add-on"><?= @text('Slug'); ?></span>
		        <input type="text" name="slug" maxlength="255" value="<?= $weblink->slug ?>" />
		    </div>
		</div>
	    <div class="scrollable">
	        <fieldset class="form-horizontal">
	        	<legend><?= @text( 'Details' ); ?></legend>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text( 'URL' ); ?></label>
				    <div class="controls">
				        <input class="required validate-url" type="text" name="url" value="<?= $weblink->url; ?>" maxlength="250" />
				    </div>
				</div>
				<div class="control-group">
				    <label class="control-label" for=""><?= @text( 'Description' ); ?></label>
				    <div class="controls">
				        <textarea rows="9" name="description"><?= $weblink->description; ?></textarea>
				    </div>
				</div>
			</fieldset>
		</div>
	</div>
	<div class="sidebar">
		<div class="scrollable">
			<fieldset class="form-horizontal">
				<legend><?= @text( 'Publish' ); ?></legend>
				<div class="control-group">
				    <label class="control-label" for="published"><?= @text( 'Published' ) ?></label>
				    <div class="controls">
				        <input type="checkbox" name="published" value="1" <?= $weblink->published ? 'checked="checked"' : '' ?> />
				    </div>
				</div>
			</fieldset>
	        <fieldset class="categories group">
	            <legend><?= @text('Category') ?></legend>
	            <div class="control-group">
	                <?= @helper('listbox.radiolist', array(
	                		'list'     => @service('com://admin/categories.model.categories')->sort('title')->table('weblinks')->getRowset(),
	                		'selected' => $weblink->categories_category_id,
	                		'name'     => 'categories_category_id',
	                        'text'     => 'title',
	                	));
	                ?>
	            </div>
	        </fieldset>
        </div>
	</div>
</form>