<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<form action="<?= helper('com:comments.route.comment', array('row' => $row)) ?>" method="post">
    <input type="hidden" name="row" value="<?= $state->row ?>" />

    <?= object('com:ckeditor.controller.editor')->render(
        array('name' => 'text', 'text' => "", 'toolbar' => 'basic')
    ); ?>

    <input class="btn btn-primary" type="submit" value="<?= translate('Comment') ?>"/>
</form>
