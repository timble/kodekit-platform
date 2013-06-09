<?
/**
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
    <div class="scopebar__group">
        <a href="<?= @route('enabled=') ?>" class="<?= is_null($state->enabled) ? 'active' : '' ?>">
            <?= @text('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= @route('enabled=1') ?>" class="<?= $state->enabled === true ? 'active' : '' ?>">
            <?= @text('Enabled') ?>
        </a>
        <a href="<?= @route('enabled=0') ?>" class="<?= $state->enabled === false ? 'active' : '' ?>">
            <?= @text('Disabled') ?>
        </a>
    </div>
    <div class="scopebar__search">
        <?= @helper('grid.search') ?>
    </div>
</div>