<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= $page->getParams('url')->render('urlparams') ?>

<? if($html = $page->getParams('layout')->render('params')) : ?>
    <?= $html ?>
<? endif ?>

<?= $page->getParams('page')->render('params'); ?>