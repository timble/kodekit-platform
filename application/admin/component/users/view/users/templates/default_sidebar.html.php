<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<h3><?= translate('Roles') ?></h3>
<ul class="navigation">
	<li>
        <a class="<?= is_null(parameters()->role) ? 'active' : ''; ?>" href="<?= route('role=') ?>">
            <?= translate('All roles') ?>
        </a>
	</li>
	<? foreach($roles as $role) : ?>
    <li>
        <a <?= parameters()->role == $role->id ? 'class="active"' : '' ?> href="<?= route('role='.$role->id) ?>">
            <?= $role->name ?>
        </a>
    </li>
	<? endforeach ?>
</ul>

<h3><?= translate('Groups') ?></h3>
<ul class="navigation">
	<li>
        <a class="<?= is_null(parameters()->group) ? 'active' : ''; ?>" href="<?= route('group=') ?>">
            <?= translate('All groups') ?>
        </a>
	</li>

	<? foreach($groups as $group) : ?>
    <li>
        <a <?= parameters()->group == $group->name ? 'class="active"' : '' ?> href="<?= route('group='.$group->name) ?>">
            <?= $group->name ?>
        </a>
    </li>
	<? endforeach ?>
</ul>