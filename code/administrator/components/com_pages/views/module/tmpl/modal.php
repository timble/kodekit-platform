<?
/**
 * @version     $Id: modal.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://com_pages/js/module.js" />

<script>
window.addEvent('domready', (function() {
    new Pages.Module({
        'module': <?= $state->module ?>,
        'page': <?= $state->page ?>,
        'form': document.forms['module-pages'],
        'parent_input_current': window.parent.document.getElement('#pages-modules input[name="modules[<?= $state->module ?>][current]"]'),
        'parent_input_others': window.parent.document.getElement('#pages-modules input[name="modules[<?= $state->module ?>][others]"]')
    });
}));
</script>

<form id="module-pages" class="form-horizontal">
    <fieldset>
	    <label class="radio inline">
	    	<input type="radio" name="pages" value="all" <?= count($relations) == 1 && $relations->top()->pages_page_id == 0 ? 'checked="checked"' : '' ?>/>
	    	<?= @text('All') ?>
	    </label>
	    <label class="radio inline">
	    	<input type="radio" name="pages" value="selected" <?= count($relations) && $relations->top()->pages_page_id != 0 ? 'checked="checked"' : '' ?>/>
	    	<?= @text('Selected') ?>
	    </label>
	    <label class="radio inline">
	    	<input type="radio" name="pages" value="none" <?= !count($relations) ? 'checked="checked"' : '' ?>/>
	    	<?= @text('None') ?>
	    </label>
	
	    <input type="button" name="save" value="<?= @text('Save') ?>" />
    </fieldset>
    <? foreach($menus as $menu) : ?>
        <? $menu_pages = $pages->find(array('pages_menu_id' => $menu->id)) ?>
        <? if(count($menu_pages)) : ?>
            <h3><?= $menu->title ?></h3>
            <? foreach($menu_pages as $page) : ?>
                <? $checked  = array_intersect(array(0, $page->id), $relations->pages_page_id) ? ' checked="checked"' : '' ?>
                <? $disabled = count($relations) && $relations->top()->pages_page_id != 0 ? '' : ' disabled="disabled"'?>
                <input type="checkbox" name="page_ids[]" value="<?= $page->id ?>" class="page-<?= $page->id ?> level<?= $page->level ?>" <?= $checked ?><?= $disabled ?> />
                <? if($page->id == $state->page) : ?>
                    <strong><?= $page->title ?></strong>
                <? else : ?>
                    <?= $page->title ?>
                <? endif ?>   
                <br>
            <? endforeach ?>
        <? endif ?>
    <? endforeach ?>
</form>
