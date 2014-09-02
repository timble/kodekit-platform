<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<div class="scopebar">
    <div class="scopebar__group">
            <a href="<?= route('published=&deleted=&access=' ) ?>" class="<?= !is_bool(parameters()->published) && !parameters()->deleted && is_null(parameters()->access) ? 'active' : '' ?>">
                <?= 'All' ?>
            </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= route('published=1' ) ?>" class="<?= parameters()->published === true ? 'active' : '' ?>">
            <?= translate('Published') ?>
        </a>
        <a href="<?= route('published=0' ) ?>" class="<?= parameters()->published === false ? 'active' : '' ?>">
            <?= translate('Unpublished') ?>
        </a>
        <a href="<?= route( parameters()->deleted ? 'deleted=' : 'deleted=1' ) ?>" class="<?= parameters()->deleted ? 'active' : '' ?>">
            <?= 'Trashed' ?>
        </a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= parameters()->access === 1 ? 'active' : ''; ?>" href="<?= route(parameters()->access === 1 ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= parameters()->hidden === 1 ? 'active' : ''; ?>" href="<?= route(parameters()->hidden === 1 ? 'hidden=' : 'hidden=1' ) ?>">
    	    <?= 'Hidden' ?>
    	</a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>
