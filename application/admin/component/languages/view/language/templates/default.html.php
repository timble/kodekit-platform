<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.keepalive') ?>
<?= helper('behavior.validator') ?>

<script src="assets://js/koowa.js" />

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="enabled" value="0" />
    <input type="hidden" name="primary" value="0" />
    
    <div class="form-content">
        <div class="grid_8">
    		<fieldset>
    			<legend><?= translate('Details') ?></legend>
    			<div>
    			    <label><?= translate('Name') ?></label>
    			    <div>
    			        <input id="name_field" type="text" name="name" value="<?= $language->name ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= translate('Native Name') ?></label>
    			    <div>
    			        <input id="native_field" type="text" name="native_name" value="<?= $language->native_name ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= translate('Slug') ?></label>
    			    <div>
    			        <input id="alias_field" type="text" name="slug" value="<?= $language->slug ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label><?= translate('ISO Code') ?></label>
    			    <div>
    			        <input type="text" name="iso_code" type="text" value="<?= $language->iso_code ?>" />
    			    </div>
    			</div>
    			<div>
    			    <label for="enabled"><?= translate('Enabled') ?></label>
    			    <div>
    			        <input type="checkbox" name="enabled" value="1" <?= $language->enabled ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    			<div>
    			    <label for="primary"><?= translate('Primary') ?></label>
    			    <div>
    			        <input type="checkbox" name="primary" value="1" <?= $language->primary ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    		</fieldset>
    	</div>
	</div>
</form>