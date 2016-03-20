<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? foreach($categories as $category) : ?>
<div>
    <div class="page-header">
        <h1>
            <a href="<?= helper('route.category', array('entity' => $category)) ?>">
                <?= escape($category->title);?>
            </a>
        </h1>
    </div>

    <? if($category->attachments_attachment_id) : ?>
        <a href="<?= helper('route.category', array('entity' => $category)) ?>">
            <figure>
                <?= helper('com:attachments.image.thumbnail', array(
                    'attachment' => $category->attachments_attachment_id,
                    'attribs' => array('width' => '200', 'align' => 'right', 'class' => 'thumbnail'))) ?>
            </figure>
        </a>
    <? endif ?>

    <? if ($category->description) : ?>
    <p><?= $category->description; ?></p>
    <? endif; ?>

    <a href="<?= helper('route.category', array('entity' => $category)) ?>"><?= translate('Read more') ?></a>
</div>
<? endforeach; ?>
