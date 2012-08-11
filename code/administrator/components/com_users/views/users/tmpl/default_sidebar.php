<?
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h3><?= @text('Groups') ?></h3>
<nav class="scrollable">
	<a class="<?= !is_numeric($state->group) ? 'active' : ''; ?>" href="<?= @route('group=' ) ?>">
	    <?= @text('All users'); ?>
	</a>
	<? foreach($groups as $group) : ?>
    <a <?= $state->group == $group->id ? 'class="active"' : '' ?> style="padding-left: <?= ($group->depth * 15) + 22 ?>px" href="<?= @route('group='.$group->id) ?>">
        <?= $group->name ?>
    </a>
	<? endforeach ?>
</nav>