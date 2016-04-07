<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.koowa'); ?>
<?= helper('behavior.validator') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="post" class="-koowa-form" id="group-form">
    <div class="main">
        <div class="title">
            <input class="required" type="text" name="name" maxlength="255" value="<?= $group->name ?>" placeholder="<?= translate('Group name') ?>" />
        </div>
        <div class="scrollable">
            <fieldset>
                <legend><?= translate('Description') ?></legend>
                <?= object('com:ckeditor.controller.editor')->render(array('name' => 'description', 'text' => $group->description)) ?>
            </fieldset>
        </div>
    </div>
</form>
