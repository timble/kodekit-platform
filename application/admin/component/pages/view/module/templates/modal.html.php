<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:script src="assets://pages/js/module.js" />

<script>
window.addEvent('domready', (function() {
    new Pages.Module({
        'module': <?= parameters()->id ?>,
        'page': <?= parameters()->page ?>,
        'form': document.forms['module-pages'],
        'parent_input_current': window.parent.document.getElement('#pages-modules input[name="modules[<?= parameters()->id ?>][current]"]'),
        'parent_input_others': window.parent.document.getElement('#pages-modules input[name="modules[<?= parameters()->id ?>][others]"]')
    });
}));
</script>

<form id="module-pages" class="scrollable">
    <fieldset>
        <label class="radio inline">
            <input type="radio" name="pages" value="all" <?= $module->pages == 'all' ? 'checked="checked"' : '' ?>/>
            <?= translate('All') ?>
        </label>
        <label class="radio inline">
            <input type="radio" name="pages" value="selected" <?= is_array($module->pages) ? 'checked="checked"' : '' ?>/>
            <?= translate('Selected') ?>
        </label>
        <label class="radio inline">
            <input type="radio" name="pages" value="none" <?= $module->pages == 'none' ? 'checked="checked"' : '' ?>/>
            <?= translate('None') ?>
        </label>

        <input type="button" name="save" class="button" value="<?= translate('Save') ?>" />
    </fieldset>
    <? foreach($menus as $menu) : ?>
        <? $menu_pages = $pages->find(array('pages_menu_id' => $menu->id)) ?>
        <? if(count($menu_pages)) : ?>
        <fieldset>
            <legend><?= $menu->title ?></legend>
            <? foreach($menu_pages as $page) : ?>
                <? $checked  = ($module->pages == 'all' || $module->pages != 'none' && in_array($page->id, $module->pages)) ? ' checked="checked"' : '' ?>
                <? $disabled = is_array($module->pages) ? '' : ' disabled="disabled"'?>
                <label class="checkbox level<?= $page->level ?>">
                    <input type="checkbox" name="page_ids[]" value="<?= $page->id ?>" class="page-<?= $page->id ?>" <?= $checked ?><?= $disabled ?> />
                    <? if($page->id == parameters()->page) : ?>
                    <strong><?= $page->title ?></strong>
                    <? else : ?>
                    <?= $page->title ?>
                    <? endif ?>
                </label>
            <? endforeach ?>
            </fieldset>
        <? endif ?>
    <? endforeach ?>
</form>
