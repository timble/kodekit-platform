<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<fieldset>
    <legend><?= translate( 'Publish' ); ?></legend>
    <div>
        <label for="published"><?= translate('Published') ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $category->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="access"><?= translate('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $category->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>
<? if($state->table == 'articles') : ?>
    <fieldset class="categories group">
        <legend><?= translate('Parent') ?></legend>
        <div>
            <?= helper('com:categories.radiolist.categories', array('row' => $category, 'name' => 'parent_id', 'filter' => array('parent' => '0', 'table' => $state->table))) ?>
        </div>
    </fieldset>
<? endif ?>

<? if($category->isAttachable()) : ?>
<fieldset>
    <legend><?= translate('Image') ?></legend>
    <? if (!$category->isNew()) : ?>
        <?= import('com:attachments.view.attachments.list.html', array('attachments' => $category->getAttachments(), 'attachments_attachment_id' => $category->attachments_attachment_id)) ?>
    <? endif ?>
    <?= import('com:attachments.view.attachments.upload.html') ?>
</fieldset>
<? endif ?>