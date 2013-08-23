<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<form action="<?= @helper('com:comments.route.comment', array('row' => $row)) ?>" method="post">
    <input type="hidden" name="row" value="<?= $state->row ?>" />
    <?= @object('com:ckeditor.controller.editor')->render(array('name' => 'text', 'text' => "", 'toolbar' => 'basic')) ?>

    <input class="btn btn-primary" type="submit" value="<?= @text('Comment') ?>"/>
</form>