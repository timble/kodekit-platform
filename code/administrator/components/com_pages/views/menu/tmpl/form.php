<?
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.validator') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="<?= @route('&id='.$menu->id)?>" method="post" class="-koowa-form">
    <div class="form-body">
        <div class="title">
            <input class="required" type="text" name="title" maxlength="255" value="<?= $menu->title ?>" placeholder="<?= @text('Title') ?>" />
        </div>
        <div class="form-content">
            <label for="name"><?= @text('Unique Name') ?>:</label>
            <input class="required" type="text" name="name" size="30" maxlength="25" value="<?= $menu->slug ?>" />

            <label for="description"><?= @text('Description') ?>:</label>
            <textarea name="description" rows="3" placeholder="<?= @text('Description') ?>" maxlength="255"><?= $menu->description ?></textarea>

            <? if(!$state->id) : ?>
            <label for="module_title"><?= @text('Module Title') ?>:</label>
            <input class="required" type="text" name="module_title" id="module_title" size="30" value="" />
            <? endif ?>
        </div>
    </div>
</form>
