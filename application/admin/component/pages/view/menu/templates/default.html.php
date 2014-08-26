<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

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
                        <?= helper('com:application.listbox.applications', array('selected' => $menu->isNew() ? state()->application : $menu->application, 'deselect' => false)) ?>
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
