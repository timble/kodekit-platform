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
    <legend><?= translate('Publish'); ?></legend>
    <div>
        <label for="published"><?= translate( 'Published' ); ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $contact->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="access"><?= translate('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $contact->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>

<fieldset>
    <legend><?= translate('Category') ?></legend>
    <?= helper('com:categories.radiolist.categories', array('row' => $contact, 'name' => 'categories_category_id')) ?>
</fieldset>

<? if($contact->isAttachable()) : ?>
    <fieldset>
        <legend><?= translate('Attachments'); ?></legend>
        <? if (!$contact->isNew()) : ?>
            <?= import('com:attachments.view.attachments.list.html', array('attachments' => $contact->getAttachments(), 'assignable' => false)) ?>
        <? endif ?>
        <? if(!count($contact->getAttachments())) : ?>
        <?= import('com:attachments.view.attachments.upload.html') ?>
        <? endif ?>
    </fieldset>
<? endif ?>

<fieldset>
    <legend><?= translate('Parameters'); ?></legend>
    <?= $contact->params->render(); ?>
</fieldset>