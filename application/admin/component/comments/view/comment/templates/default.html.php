<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://js/koowa.js" />

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" id="comment-form" class="-koowa-form">
    <input type="hidden" name="row" value="<?= $state->row ?>" />
    <input type="hidden" name="table" value="<?= $state->table ?>" />

    <?= object('com:ckeditor.controller.editor')->render(array('name' => 'text', 'toolbar' => 'basic', 'text' => $comment->text)) ?>
</form>