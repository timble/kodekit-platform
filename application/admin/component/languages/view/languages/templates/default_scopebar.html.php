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
        <a href="<?= route('enabled=') ?>" class="<?= is_null(parameters()->enabled) ? 'active' : '' ?>">
            <?= translate('All') ?>
        </a>
    </div>
    <div class="scopebar__group">
        <a href="<?= route('enabled=1') ?>" class="<?= parameters()->enabled === true ? 'active' : '' ?>">
            <?= translate('Enabled') ?>
        </a>
        <a href="<?= route('enabled=0') ?>" class="<?= parameters()->enabled === false ? 'active' : '' ?>">
            <?= translate('Disabled') ?>
        </a>
    </div>
    <div class="scopebar__search">
        <?= helper('grid.search') ?>
    </div>
</div>