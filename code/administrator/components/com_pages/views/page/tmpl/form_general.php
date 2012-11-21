<?
/**
 * @version     $Id: form_general.php 3035 2011-10-09 16:57:12Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="status"><?= @text('Published') ?></label>
        <div class="controls">
            <input type="checkbox" name="published" value="1" <?= $page->isNew() || $page->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="hidden"><?= @text('Hidden') ?></label>
        <div class="controls">
            <input type="checkbox" name="hidden" value="1" <?= $page->hidden ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="access"><?= @text('Registered') ?></label>
        <div class="controls">
            <input type="checkbox" name="access" value="1" <?= $page->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="access"><?= @text('Group') ?></label>
        <div class="controls">
            <?= @helper('com://admin/users.template.helper.listbox.groups', array('selected' => $page->users_group_id, 'name' => 'users_group_id')) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="parent"><?= @text('Menu') ?></label>
        <div id="parent" class="controls">
            <?= @helper('listbox.menus', array('selected' => $state->menu)) ?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="parent"><?= @text('Parent') ?></label>
        <div id="pages-parent" class="controls">
            <?= @helper('listbox.parents', array('page' => $page, 'menu' => $state->menu, 'selected' => $page->parent_id, 'attribs')) ?>
        </div>
    </div>
    <?
        switch($state->type['name'])
        {
            case 'redirect':
                echo @template('form_redirect');
                break;

            case 'pagelink':
                echo @template('form_pagelink');
                break;
        }
    ?>
</fieldset>
