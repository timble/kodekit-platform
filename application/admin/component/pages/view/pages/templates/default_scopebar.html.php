<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<div class="scopebar">
    <div class="scopebar__group">
            <a href="<?= route('published=&deleted=&access=' ) ?>" class="<?= !is_bool($state->published) && !$state->deleted && is_null($state->access) ? 'active' : '' ?>">
                <?= 'All' ?>
            </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= route('published=1' ) ?>" class="<?= $state->published === true ? 'active' : '' ?>">
            <?= translate('Published') ?>
        </a>
        <a href="<?= route('published=0' ) ?>" class="<?= $state->published === false ? 'active' : '' ?>">
            <?= translate('Unpublished') ?>
        </a>
        <a href="<?= route( $state->deleted ? 'deleted=' : 'deleted=1' ) ?>" class="<?= $state->deleted ? 'active' : '' ?>">
            <?= 'Trashed' ?>
        </a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= $state->access === 1 ? 'active' : ''; ?>" href="<?= route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= $state->hidden === 1 ? 'active' : ''; ?>" href="<?= route($state->hidden === 1 ? 'hidden=' : 'hidden=1' ) ?>">
    	    <?= 'Hidden' ?>
    	</a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>
