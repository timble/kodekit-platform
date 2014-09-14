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
        <a class="<?= is_null(parameters()->published) ? 'active' : ''; ?>" href="<?= route('published=' ) ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a class="<?= parameters()->published === true ? 'active' : ''; ?>" href="<?= route(parameters()->published === true ? 'published=' : 'published=1') ?>">
            <?= translate('Published') ?>
        </a>
        <a class="<?= parameters()->published === false ? 'active' : ''; ?>" href="<?= route(parameters()->published === false ? 'published=' : 'published=0' ) ?>">
            <?= translate('Unpublished') ?>
        </a>
    </div>
    <div class="scopebar__group">
    	<a class="<?= parameters()->access === true ? 'active' : ''; ?>" href="<?= route(parameters()->access === true ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>