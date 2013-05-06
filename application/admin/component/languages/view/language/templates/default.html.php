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

<script src="media://js/koowa.js" />

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="enabled" value="0" />
    <input type="hidden" name="primary" value="0" />
    
    <div class="form-content">
        <div class="grid_8">
    		<fieldset>
    			<legend><?= @text('Details') ?></legend>
    			<div>
    			    <label><?= @text('Name') ?></label>
    			    <div>
    			        <input id="name_field" type="text" name="name" value="<?= $language->name ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= @text('Native Name') ?></label>
    			    <div>
    			        <input id="native_field" type="text" name="native_name" value="<?= $language->native_name ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= @text('Slug') ?></label>
    			    <div>
    			        <input id="alias_field" type="text" name="slug" value="<?= $language->slug ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= @text('ISO Code') ?></label>
    			    <div>
    			        <input type="text" name="iso_code" type="text" value="<?= $language->iso_code ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label for="enabled"><?= @text('Enabled') ?></label>
    			    <div>
    			        <input type="checkbox" name="enabled" value="1" <?= $language->enabled ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    			<div>
    			    <label for="primary"><?= @text('Primary') ?></label>
    			    <div>
    			        <input type="checkbox" name="primary" value="1" <?= $language->primary ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    		</fieldset>
    	</div>
	</div>
</form>