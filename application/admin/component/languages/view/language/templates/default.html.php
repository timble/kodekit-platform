<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.keepalive') ?>
<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="enabled" value="0" />
    <input type="hidden" name="default" value="0" />

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
    			    <label for="default"><?= translate('Default') ?></label>
    			    <div>
    			        <input type="checkbox" name="default" value="1" <?= $language->default ? 'checked="checked"' : '' ?> />
    			    </div>
    			</div>
    		</fieldset>
    	</div>
	</div>
</form>