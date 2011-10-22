<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= ($state->action || $state->application) ? '' : 'active'; ?>">
			<a href="<?= @route('application=&action=&created_on=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		
		<li class="<?= ($state->application == 'site') ? 'active' : ''; ?> separator-left">
			<a href="<?= @route('application=site&action='.$state->action ) ?>">
			    <?= @text('Site') ?>
			</a>
		</li>

		<li class="<?= ($state->application == 'admin') ? 'active' : ''; ?> <?= count($actions) ? 'separator-right': ''?>">
			<a href="<?= @route('application=admin&action='.$state->action ) ?>">
			    <?= @text('Administrator') ?>
			</a>
		</li>
		
		<? foreach ($actions as $action):?>
		<li class="<?= ($state->action == $action->action) ? 'active' : ''; ?>">
			<a href="<?= @route('action='.$action->action) ?>">
			    <?= ucfirst($action->action) ?>
			</a>
		</li>
		<? endforeach ?>

		<li class="<?= ($state->direction == 'desc') ? 'active' : ''; ?> separator-left">
			<a href="<?= @route('direction=desc' ) ?>">
			    <?= @text('Latest First') ?>
			</a>
		</li>
		<li class="<?= ($state->direction == 'asc') ? 'active' : ''; ?>">
			<a href="<?= @route('direction=asc' ) ?>">
			    <?= @text('Oldest First') ?>
			</a>
		</li>
	</ul>
</div>