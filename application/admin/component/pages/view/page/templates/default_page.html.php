<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? if($state->type['name'] == 'component') : ?>

<? $model = $this->getView()->getModel() ?>

<?= $page->getParams('url')->render('urlparams') ?>

<? if($rendered_params = $page->getParams('layout')->render('params')) : ?>
    <?= $rendered_params ?>
<? endif ?>

<?= $page->getParams('page')->render('params'); ?>
<? endif ?>

<? if($state->type['name'] == 'redirect') : ?>
    <div id="page-link-type">
        <label for="parent"><?= @text('Type') ?></label>
        <div id="parent" class="controls">
            <label class="radio">
                <input type="radio" name="link_type" value="id" <?= $page->isNew() || $page->link_id ? 'checked="checked"' : '' ?> />
                <?= @text('Page') ?>
            </label>
    
            <label class="radio">
                <input type="radio" name="link_type" value="url" <?= $page->link_url ? 'checked="checked"' : '' ?> />
                <?= @text('URL') ?>
            </label>
        </div>
    </div>
    <div id="page-link-id">
        <label for="parent"><?= @text('Page') ?></label>
        <div id="parent" class="controls">
            <?= @helper('listbox.pages', array('name' => 'link_id', 'selected' => $page->link_id)) ?>
        </div>
    </div>
    <div id="page-link-url">
        <label for="parent"><?= @text('URL') ?></label>
        <div id="parent" class="controls">
            <input type="text" name="link_url" value="<?= $page->link_url ?>" />
        </div>
    </div>
<? endif ?>

<? if($state->type['name'] == 'pagelink') : ?>
    <div>
        <label for="parent"><?= @text('Page') ?></label>
        <div id="parent" class="controls">
            <?= @helper('listbox.pages', array('name' => 'link_id', 'selected' => $page->link_id)) ?>
        </div>
    </div>
<? endif ?>