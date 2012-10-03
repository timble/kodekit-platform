<?
/**
 * @version     $Id: list.php 4523 2012-08-11 18:30:46Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>
<h3><?= @text('Roles') ?></h3>
<nav class="scrollable">
	<a class="<?= is_null($state->role) ? 'active' : ''; ?>" href="<?= @route('role=') ?>">
	    <?= @text('All roles') ?>
	</a>
	<? foreach($groups->find(array('type' => 'role')) as $role) : ?>
        <a <?= $state->role == $role->id ? 'class="active"' : '' ?> href="<?= @route('role='.$role->id) ?>">
            <?= $role->name ?>
        </a>
	<? endforeach ?>
</nav>

<h3><?= @text('Groups') ?></h3>
<nav class="scrollable">
	<a class="<?= is_null($state->group) ? 'active' : ''; ?>" href="<?= @route('group=') ?>">
	    <?= @text('All groups') ?>
	</a>
	
	<? foreach($groups->find(array('type' => 'group')) as $group) : ?>
        <a <?= $state->group == $group->id ? 'class="active"' : '' ?> href="<?= @route('group='.$group->id) ?>">
            <?= $group->name ?>
        </a>
	<? endforeach ?>
</nav>