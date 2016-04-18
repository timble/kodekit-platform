<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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
<? if(parameters()->table == 'articles') : ?>
    <fieldset class="categories group">
        <legend><?= translate('Parent') ?></legend>
        <div>
            <?= helper('com:categories.radiolist.categories', array('entity' => $category, 'name' => 'parent_id', 'filter' => array('parent' => '0', 'table' => parameters()->table))) ?>
        </div>
    </fieldset>
<? endif ?>

<? if($category->isAttachable()) : ?>
<fieldset>
    <legend><?= translate('Image') ?></legend>
    <? if (!$category->isNew()) : ?>
        <?= import('com:attachments/attachments/list.html', array('attachments' => $category->getAttachments(), 'attachments_attachment_id' => $category->attachments_attachment_id)) ?>
    <? endif ?>
    <?= import('com:attachments/attachments/upload.html') ?>
</fieldset>
<? endif ?>