<?php
/**
 * @version     $Id: default_filter.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<div class="scopebar">
    <div class="scopebar-group">
            <a href="<?= @route('enabled=&deleted=' ) ?>" class="<?= !is_bool($state->enabled) && !$state->deleted ? 'active' : '' ?>">
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
    <div class="scopebar-search">
        <?= @helper('grid.search') ?>
    </div>
</div>
