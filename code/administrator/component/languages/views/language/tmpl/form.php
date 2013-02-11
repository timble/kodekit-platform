<?
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />

<?= @template('com://admin/default.view.form.toolbar') ?>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="enabled" value="0" />
    <input type="hidden" name="primary" value="0" />
    
    <div class="form-content">
        <div class="grid_8">
    		<fieldset class="form-horizontal">
    			<legend><?= @text('Details') ?></legend>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Name') ?></label>
    			    <div class="controls">
    			        <input id="name_field" type="text" name="name" value="<?= $language->name ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Native Name') ?></label>
    			    <div class="controls">
    			        <input id="native_field" type="text" name="native_name" value="<?= $language->native_name ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Slug') ?></label>
    			    <div class="controls">
    			        <input id="alias_field" type="text" name="slug" value="<?= $language->slug ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('ISO Code') ?></label>
    			    <div class="controls">
    			        <input type="text" name="iso_code" type="text" value="<?= $language->iso_code ?>" />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label" for="enabled"><?= @text('Enabled') ?></label>
    			    <div class="controls">
    			        <input type="checkbox" name="enabled" value="1" <?= $language->enabled ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label" for="primary"><?= @text('Primary') ?></label>
    			    <div class="controls">
    			        <input type="checkbox" name="primary" value="1" <?= $language->primary ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    		</fieldset>
    	</div>
	</div>
</form>