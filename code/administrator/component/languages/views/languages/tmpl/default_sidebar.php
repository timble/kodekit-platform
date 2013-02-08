<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Applications') ?></h3>
<nav class="scrollable">
    <a class="<?= $state->application == 'admin' ? 'active' : '' ?>" href="<?= @route('application=admin') ?>">
        <?= @text('Administrator') ?>
    </a>
    <a class="<?= $state->application == 'site' ? 'active' : '' ?>" href="<?= @route('application=site') ?>">
        <?= @text('Site') ?>
    </a>
</nav>