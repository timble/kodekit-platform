<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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
        <?= helper('com:users.listbox.groups', array('selected' => $page->access_group, 'name' => 'access_group')) ?>
    </div>
</div>
<div>
    <label for="parent"><?= translate('Menu') ?></label>
    <div id="parent" class="controls">
        <?= helper('listbox.menus', array('selected' => $menu->id)) ?>
    </div>
</div>
<div>
    <label for="parent"><?= translate('Parent') ?></label>
    <div id="pages-parent" class="controls">
        <?= helper('listbox.parents', array('page' => $page, 'menu' => $menu->id, 'selected' => $parent_id, 'attribs')) ?>
    </div>
</div>
