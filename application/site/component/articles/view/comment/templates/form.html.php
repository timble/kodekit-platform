<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<form action="<?= helper('route.comment', array('entity' => $entity)) ?>" method="post">
    <input type="hidden" name="row" value="<?= parameter('row') ?>" />

    <?= object('com:ckeditor.controller.editor')->render(
        array('name' => 'text', 'text' => "", 'toolbar' => 'basic')
    ); ?>

    <input class="btn btn-primary" type="submit" value="<?= translate('Comment') ?>"/>
</form>
