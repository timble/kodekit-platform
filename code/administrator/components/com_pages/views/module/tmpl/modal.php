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

<form id="module-pages">
    <input type="radio" name="pages" value="all" <?= count($relations) == 1 && $relations->top()->pages_page_id == 0 ? 'checked="checked"' : '' ?>/>
    <label><?= @text('All') ?></label>

    <input type="radio" name="pages" value="selected" <?= count($relations) && $relations->top()->pages_page_id != 0 ? 'checked="checked"' : '' ?>/>
    <label><?= @text('Selected') ?></label>

    <input type="radio" name="pages" value="none" <?= !count($relations) ? 'checked="checked"' : '' ?>/>
    <label><?= @text('None') ?></label>

    <input type="button" name="save" value="<?= @text('Save') ?>" />

    <?= @helper('tabs.startPane') ?>
    <?= @helper('tabs.startPanel', array('title' => @text('All pages'))) ?>
        <? foreach($pages as $page) : ?>
            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - 1) ?><sup>|_</sup>&nbsp;
            <? $checked  = array_intersect(array(0, $page->id), $relations->pages_page_id) ? ' checked="checked"' : '' ?>
            <input type="checkbox" name="page_ids[]" value="<?= $page->id ?>" class="page-<?= $page->id ?>" <?= $checked ?>/>
            <?= $page->title ?><br>
        <? endforeach ?>
    <?= @helper('tabs.endPanel') ?>
    <?= @helper('tabs.startPanel', array('title' => @text('Child pages'))) ?>
        <? foreach($pages as $page) : ?>
            <? if(in_array($state->page, $page->parent_ids)) : ?>
                <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - array_search($state->page, $page->parent_ids) - 1) ?><sup>|_</sup>&nbsp;
                <?= $page->title ?><br>
            <? endif ?>
        <? endforeach ?>
    <?= @helper('tabs.endPanel') ?>
    <?= @helper('tabs.endPane') ?>
</form>
