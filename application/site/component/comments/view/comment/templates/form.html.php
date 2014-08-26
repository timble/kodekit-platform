<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<form action="<?= helper('com:comments.route.comment', array('entity' => $entity)) ?>" method="post">
    <input type="hidden" name="row" value="<?= state()->row ?>" />

    <?= object('com:ckeditor.controller.editor')->render(
        array('name' => 'text', 'text' => "", 'toolbar' => 'basic')
    ); ?>

    <input class="btn btn-primary" type="submit" value="<?= translate('Comment') ?>"/>
</form>
