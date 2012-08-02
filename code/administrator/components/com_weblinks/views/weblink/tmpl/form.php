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

defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>
<?= @helper('behavior.validator'); ?>

<?= @template('com://admin/default.view.form.toolbar'); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->
<form action="" method="post" id="weblink-form" class="-koowa-form">
	<input type="hidden" name="id" value="<?= $weblink->id ?>" />

	<div class="form-body">
		<div class="title">
		    <input class="required" type="text" name="title" maxlength="255" value="<?= $weblink->title ?>" placeholder="<?= @text('Title') ?>" />
		</div>
	    
	    <div class="form-content">
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
		<fieldset class="form-horizontal">
			<legend><?= @text( 'Publish' ); ?></legend>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text( 'Published' ) ?></label>
			    <div class="controls">
			        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $weblink->enabled)) ?>
			    </div>
			</div>
			<div class="control-group">
			    <label class="control-label" for=""><?= @text( 'Slug' ); ?></label>
			    <div class="controls">
			        <input type="text" name="slug" maxlength="255" value="<?= $weblink->slug; ?>" placeholder="<?= @text( 'Slug' ); ?>" />
			    </div>
			</div>
		</fieldset>
        <fieldset class="categories group">
            <legend><?= @text('Category') ?></legend>
            <div class="control-group">
                <?= @helper('com://admin/categories.template.helper.listbox.categories', array(
                'name'      => 'category_id',
                'selected'  => $weblink->category_id,
                'attribs'   => array('id' => 'category_id', 'class' => 'required'),
                'deselect'  => false,
                'table'     => 'weblinks'
            )) ?>
            </div>
        </fieldset>
	</div>
</form>