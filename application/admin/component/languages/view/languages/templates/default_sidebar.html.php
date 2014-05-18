<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= translate('Applications') ?></h3>
<ul class="navigation">
    <li>
        <a class="<?= $state->application == 'admin' ? 'active' : '' ?>" href="<?= route('application=admin') ?>">
            <?= translate('Administrator') ?>
        </a>
    </li>
    <li>
        <a class="<?= $state->application == 'site' ? 'active' : '' ?>" href="<?= route('application=site') ?>">
            <?= translate('Site') ?>
        </a>
    </li>
</ul>