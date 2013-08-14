<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h3><?= @text('Roles') ?></h3>
<ul class="navigation">
	<li>
        <a class="<?= is_null($state->role) ? 'active' : ''; ?>" href="<?= @route('role=') ?>">
            <?= @text('All roles') ?>
        </a>
	</li>
	<? foreach($roles as $role) : ?>
    <li>
        <a <?= $state->role == $role->id ? 'class="active"' : '' ?> href="<?= @route('role='.$role->id) ?>">
            <?= $role->name ?>
        </a>
    </li>
	<? endforeach ?>
</ul>

<h3><?= @text('Groups') ?></h3>
<ul class="navigation">
	<li>
        <a class="<?= is_null($state->group) ? 'active' : ''; ?>" href="<?= @route('group=') ?>">
            <?= @text('All groups') ?>
        </a>
	</li>

	<? foreach($groups as $group) : ?>
    <li>
        <a <?= $state->group == $group->id ? 'class="active"' : '' ?> href="<?= @route('group='.$group->id) ?>">
            <?= $group->name ?>
        </a>
    </li>
	<? endforeach ?>
</ul>