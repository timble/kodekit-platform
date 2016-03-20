<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="post" class="-koowa-form">
    <input type="hidden" name="application" value="site" />

    <div class="main">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $menu->title ?>" placeholder="<?= translate('Title') ?>" />
            <div class="slug">
                <span class="add-on"><?= translate('Slug') ?></span>
                <input type="text" name="slug" maxlength="250" value="<?= $menu->slug ?>" />
            </div>
        </div>
        <div class="scrollable">
            <fieldset>
                <div>
                    <label for="application"><?= translate('Application') ?></label>
                    <div>
                        <?= helper('com:application.listbox.applications', array(
                            'selected' => $menu->isNew() ? parameters()->application : $menu->application,
                            'deselect' => false))
                        ?>
                    </div>
                </div>
                <div>
                    <label for="description"><?= translate('Description') ?></label>
                    <div>
                        <textarea name="description" rows="3" placeholder="<?= translate('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>
                    </div>
                </div>
            </fieldset>
        </div>
    </div>
</form>
