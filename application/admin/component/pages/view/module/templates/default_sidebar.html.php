<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset class="form-horizontal">
    <legend><?= @text('Publish') ?></legend>
    <div class="control-group">
        <label class="control-label" for="published"><?= @text('Published') ?></label>
        <div class="controls">
            <input type="checkbox" name="published" value="1" <?= $module->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for=""><?= @text('Position') ?></label>
        <div class="controls">
            <?= @helper('listbox.positions', array('name' => 'position', 'selected' => $module->position, 'application' => $state->application, 'deselect' => false)) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="access"><?= @text('Registered') ?></label>
        <div class="controls">
            <input type="checkbox" name="access" value="1" <?= $module->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>