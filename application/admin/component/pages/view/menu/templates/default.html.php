<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<!--
<script src="assets://js/koowa.js" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">!
</ktml:module>

<form action="<?= route('&id='.$menu->id)?>" method="post" class="-koowa-form">
    <input type="hidden" name="application" value="site" />
    
    <div class="main">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $menu->title ?>" placeholder="<?= translate('Title') ?>" />
        </div>
        <div class="scrollable">
            <label for="name"><?= translate('Slug') ?>:</label>
            <input type="text" name="slug" size="30" maxlength="25" value="<?= $menu->slug ?>" />

            <label for="description"><?= translate('Application') ?>:</label>
            <?= helper('com:application.listbox.applications', array('selected' => $menu->isNew() ? $state->application : $menu->application)) ?>
            
            <label for="description"><?= translate('Description') ?>:</label>
            <textarea name="description" rows="3" placeholder="<?= translate('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>
        </div>
    </div>
</form>
