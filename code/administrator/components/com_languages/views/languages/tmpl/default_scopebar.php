<?
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
    <div class="scopebar-group">
        <a href="<?= @route('enabled=') ?>" class="<?= is_null($state->enabled) ? 'active' : '' ?>">
            <?= @text('All') ?>
        </a>
    </div>
    <div class="scopebar-group">
        <a href="<?= @route('enabled=1') ?>" class="<?= $state->enabled === true ? 'active' : '' ?>">
            <?= @text('Enabled') ?>
        </a>
        <a href="<?= @route('enabled=0') ?>" class="<?= $state->enabled === false ? 'active' : '' ?>">
            <?= @text('Unpublished') ?>
        </a>
    </div>
    <div class="scopebar-search">
        <?= @helper('grid.search') ?>
    </div>
</div>