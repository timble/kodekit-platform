<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<fieldset>
    <legend><?= translate('Publish') ?></legend>
    <div>
        <label for="published"><?= translate('Published') ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $module->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for=""><?= translate('Position') ?></label>
        <div>
            <?= helper('listbox.positions', array(
                'name'        => 'position',
                'selected'    => $module->position,
                'application' => parameter('application'),
                'deselect'    => false
            )) ?>
        </div>
    </div>
    <div>
        <label for="access"><?= translate('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $module->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>