<?
/**
 * @version     $Id: default_filter.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
    <div class="scopebar-group">
            <a href="<?= @route('enabled=&deleted=&access=' ) ?>" class="<?= !is_bool($state->enabled) && !$state->deleted && is_null($state->access) ? 'active' : '' ?>">
                <?= 'All' ?>
            </a>
    </div>
    <div class="scopebar-group">
        <a href="<?= @route('enabled=1' ) ?>" class="<?= $state->enabled === true ? 'active' : '' ?>">
            <?= @text('Published') ?>
        </a>
        <a href="<?= @route('enabled=0' ) ?>" class="<?= $state->enabled === false ? 'active' : '' ?>">
            <?= @text('Unpublished') ?>
        </a>
        <a href="<?= @route( $state->deleted ? 'deleted=' : 'deleted=1' ) ?>" class="<?= $state->deleted ? 'active' : '' ?>">
            <?= 'Trashed' ?>
        </a>
    </div>
    <div class="scopebar-group">
    	<a class="<?= $state->access === 1 ? 'active' : ''; ?>" href="<?= @route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
    	    <?= 'Registered' ?>
    	</a>
    </div>
    <div class="scopebar-search">
        <?= @helper('grid.search') ?>
    </div>
</div>
