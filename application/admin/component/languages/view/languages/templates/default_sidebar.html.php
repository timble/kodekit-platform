<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<h3><?= translate('Applications') ?></h3>
<ul class="navigation">
    <li>
        <a class="<?= parameters()->application == 'admin' ? 'active' : '' ?>" href="<?= route('application=admin') ?>">
            <?= translate('Administrator') ?>
        </a>
    </li>
    <li>
        <a class="<?= parameters()->application == 'site' ? 'active' : '' ?>" href="<?= route('application=site') ?>">
            <?= translate('Site') ?>
        </a>
    </li>
</ul>