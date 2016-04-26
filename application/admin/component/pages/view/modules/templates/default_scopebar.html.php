<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<div class="scopebar">
    <div class="scopebar__group">
        <a class="<?= is_null(parameter('published')) && is_null(parameter('access')) ? 'active' : ''; ?>" href="<?= route('published=&access=' ) ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a class="<?= parameter('published') === true ? 'active' : ''; ?>" href="<?= route(parameter('published') === true ? 'published=' : 'published=1') ?>">
            <?= translate('Published') ?>
        </a>
        <a class="<?= parameter('published') === false ? 'active' : ''; ?>" href="<?= route(parameter('published') === false ? 'published=' : 'published=0' ) ?>">
            <?= translate('Unpublished') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a class="<?= parameter('access') === 1 ? 'active' : ''; ?>" href="<?= route(parameter('access') === 1 ? 'access=' : 'access=1' ) ?>">
            <?= 'Registered' ?>
        </a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>