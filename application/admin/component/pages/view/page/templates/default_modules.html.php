<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>
<?= helper('behavior.modal') ?>

<div>
    <label for="modules"><?= translate('Module assignment') ?></label>
    <div>
        <? foreach($modules->available as $module) : ?>
            <input type="hidden" name="modules[<?= $module->id ?>][others]" value="" />
            <label class="checkbox">
                <? $checked = count($modules->assigned->find(array('pages_module_id' => $module->id))) ? 'checked="checked"' : '' ?>
                <input type="checkbox" name="modules[<?= $module->id ?>][current]" value="1" class="module-<?= $module->id ?>" <?= $checked ?>/>
                <a class="modal" href="<?= route('option=com_pages&view=module&layout=modal&tmpl=overlay&id='.$module->id.'&page='.$page->id) ?>" rel="{handler: 'iframe', size: {x: 400, y: 600}}">
                    <?= $module->title ?>
                </a>
            </label>
        <? endforeach ?>
    </div>
</div>

