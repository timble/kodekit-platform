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
        <a href="<?= route('enabled=') ?>" class="<?= is_null(state()->enabled) ? 'active' : '' ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= route('enabled=1') ?>" class="<?= state()->enabled === true ? 'active' : '' ?>">
            <?= translate('Enabled') ?>
        </a>
        <a href="<?= route('enabled=0') ?>" class="<?= state()->enabled === false ? 'active' : '' ?>">
            <?= translate('Disabled') ?>
        </a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>