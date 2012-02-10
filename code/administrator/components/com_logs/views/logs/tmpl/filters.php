<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

<div id="filter" class="group">
	<ul>
		<li class="<?= ($state->action || $state->application) ? '' : 'active'; ?> separator-right">
			<a href="<?= @route('application=&action=&created_on=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>

		<li class="<?= ($state->application == 'admin') ? 'active' : ''; ?>">
			<a href="<?= @route('application=admin&action='.$state->action ) ?>">
			    <?= @text('Administrator') ?>
			</a>
		</li>
		<li class="<?= ($state->application == 'site') ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('application=site&action='.$state->action ) ?>">
			    <?= @text('Frontend') ?>
			</a>
		</li>

		<?php if (count($actions)): $i = 0;?>
		<?php foreach ($actions as $action): $i++?>
		<li class="<?= ($state->action == $action->action) ? 'active' : ''; ?> <?= ($i >= count($actions)) ? 'separator-right': ''?>">
			<a href="<?= @route('action='.$action->action) ?>">
			    <?= ucfirst($action->action) ?>
			</a>
		</li>
		<?php endforeach ?>
		<?php endif ?>

		<li class="<?= ($state->direction == 'desc') ? 'active' : ''; ?>">
			<a href="<?= @route('direction=desc' ) ?>">
			    <?= @text('Latest First') ?>
			</a>
		</li>
		<li class="<?= ($state->direction == 'asc') ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('direction=asc' ) ?>">
			    <?= @text('Oldest First') ?>
			</a>
		</li>
	</ul>
</div>