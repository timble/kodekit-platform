<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= $page->getParams('url')->render('urlparams') ?>

<? if($html = $page->getParams('layout')->render('params')) : ?>
    <?= $html ?>
<? endif ?>

<?= $page->getParams('page')->render('params'); ?>