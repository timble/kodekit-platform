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
        <a class="<?= is_null(state()->published) && is_null(state()->access) ? 'active' : ''; ?>" href="<?= route('published=&access=' ) ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a class="<?= state()->published === true ? 'active' : ''; ?>" href="<?= route(state()->published === true ? 'published=' : 'published=1') ?>">
            <?= translate('Published') ?>
        </a>
        <a class="<?= state()->published === false ? 'active' : ''; ?>" href="<?= route(state()->published === false ? 'published=' : 'published=0' ) ?>">
            <?= translate('Unpublished') ?>
        </a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= state()->access === 1 ? 'active' : ''; ?>" href="<?= route(state()->access === 1 ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>