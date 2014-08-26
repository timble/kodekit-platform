<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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
            <?= helper('listbox.positions', array('name' => 'position', 'selected' => $module->position, 'application' => state()->application, 'deselect' => false)) ?>
        </div>
    </div>
    <div>
        <label for="access"><?= translate('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $module->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>