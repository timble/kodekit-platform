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
            <a href="<?= route('published=&deleted=&access=' ) ?>" class="<?= !is_bool(parameter('published')) && !parameter('deleted') && is_null(parameter('access')) ? 'active' : '' ?>">
                <?= 'All' ?>
            </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= route('published=1' ) ?>" class="<?= parameter('published') === true ? 'active' : '' ?>">
            <?= translate('Published') ?>
        </a>
        <a href="<?= route('published=0' ) ?>" class="<?= parameter('published') === false ? 'active' : '' ?>">
            <?= translate('Unpublished') ?>
        </a>
        <a href="<?= route( parameters()->deleted ? 'deleted=' : 'deleted=1' ) ?>" class="<?= parameter('deleted') ? 'active' : '' ?>">
            <?= 'Trashed' ?>
        </a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= parameter('access') === 1 ? 'active' : ''; ?>" href="<?= route(parameter('access') === 1 ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= parameter('hidden') === 1 ? 'active' : ''; ?>" href="<?= route(parameter('hidden') === 1 ? 'hidden=' : 'hidden=1' ) ?>">
    	    <?= 'Hidden' ?>
    	</a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>
