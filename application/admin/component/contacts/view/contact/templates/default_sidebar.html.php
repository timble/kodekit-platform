<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<fieldset>
    <legend><?= @text('Publish'); ?></legend>
    <div>
        <label for="published"><?= @text( 'Published' ); ?></label>
        <div>
            <input type="checkbox" name="published" value="1" <?= $contact->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div>
        <label for="access"><?= @text('Registered') ?></label>
        <div>
            <input type="checkbox" name="access" value="1" <?= $contact->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>

<fieldset class="categories group">
    <legend><?= @text('Category') ?></legend>
    <div>
        <?= @helper('listbox.radiolist', array(
            'list'     => @object('com:categories.model.categories')->sort('title')->table('contacts')->getRowset(),
            'selected' => $contact->categories_category_id,
            'name'     => 'categories_category_id',
            'text'     => 'title',
        ));
        ?>
    </div>
</fieldset>

<? if($contact->isAttachable()) : ?>
    <fieldset>
        <legend><?= @text('Attachments'); ?></legend>
        <? if (!$contact->isNew()) : ?>
            <?= @template('com:attachments.view.attachments.list.html', array('attachments' => $contact->getAttachments(), 'assignable' => false)) ?>
        <? endif ?>
        <? if(!count($contact->getAttachments())) : ?>
        <?= @template('com:attachments.view.attachments.upload.html') ?>
        <? endif ?>
    </fieldset>
<? endif ?>

<fieldset>
    <legend><?= @text('Parameters'); ?></legend>
    <?= $contact->params->render(); ?>
</fieldset>