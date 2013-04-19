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

<fieldset class="form-horizontal">
    <legend><?= @text('Publish'); ?></legend>
    <div class="control-group">
        <label class="control-label" for="published"><?= @text( 'Published' ); ?></label>
        <div class="controls">
            <input type="checkbox" name="published" value="1" <?= $contact->published ? 'checked="checked"' : '' ?> />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="access"><?= @text('Registered') ?></label>
        <div class="controls">
            <input type="checkbox" name="access" value="1" <?= $contact->access ? 'checked="checked"' : '' ?> />
        </div>
    </div>
</fieldset>

<fieldset class="categories group">
    <legend><?= @text('Category') ?></legend>
    <div class="control-group">
        <?= @helper('listbox.radiolist', array(
            'list'     => @service('com:categories.model.categories')->sort('title')->table('contacts')->getRowset(),
            'selected' => $contact->categories_category_id,
            'name'     => 'categories_category_id',
            'text'     => 'title',
        ));
        ?>
    </div>
</fieldset>

<fieldset class="form-horizontal">
    <legend><?= @text('Parameters'); ?></legend>
    <?= $contact->params->render(); ?>
</fieldset>