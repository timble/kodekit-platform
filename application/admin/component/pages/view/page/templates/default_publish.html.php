<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<div>
    <label for="status"><?= translate('Published') ?></label>
    <div>
        <input type="checkbox" name="published" value="1" <?= $page->isNew() || $page->published ? 'checked="checked"' : '' ?> />
    </div>
</div>
<div>
    <label for="hidden"><?= translate('Hidden') ?></label>
    <div>
        <input type="checkbox" name="hidden" value="1" <?= $page->hidden ? 'checked="checked"' : '' ?> />
    </div>
</div>
<div>
    <label for="access"><?= translate('Registered') ?></label>
    <div>
        <input type="checkbox" name="access" value="1" <?= $page->access ? 'checked="checked"' : '' ?> />
    </div>
</div>
<div>
    <label for="access"><?= translate('Group') ?></label>
    <div>
        <?= helper('com:users.listbox.groups', array('selected' => $page->users_group_id, 'name' => 'users_group_id')) ?>
    </div>
</div>
<div>
    <label for="parent"><?= translate('Menu') ?></label>
    <div id="parent" class="controls">
        <?= helper('listbox.menus', array('selected' => $state->menu)) ?>
    </div>
</div>
<div>
    <label for="parent"><?= translate('Parent') ?></label>
    <div id="pages-parent" class="controls">
        <?= helper('listbox.parents', array('page' => $page, 'menu' => $state->menu, 'selected' => $parent_id, 'attribs')) ?>
    </div>
</div>
