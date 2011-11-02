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
		<li class="<?= is_null($state->action) && is_null($state->application) && is_null($state->direction) ? 'active' : ''; ?>">
			<a href="<?= @route('application=&action=&direction=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		
		<li class="<?= ($state->application == 'site') ? 'active' : ''; ?> separator-left">
			<a href="<?= @route($state->application == 'site' ? 'application=' : 'application=site') ?>">
			    <?= @text('Site') ?>
			</a>
		</li>

		<li class="<?= ($state->application == 'admin') ? 'active' : ''; ?> <?= count($actions) ? 'separator-right': ''?>">
			<a href="<?= @route($state->application == 'admin' ? 'application=' : 'application=admin' ) ?>">
			    <?= @text('Administrator') ?>
			</a>
		</li>

		<li class="<?= ($state->direction == 'desc') ? 'active' : ''; ?> separator-left">
			<a href="<?= @route($state->direction == 'desc' ? 'direction=' : 'direction=desc' ) ?>">
			    <?= @text('Latest First') ?>
			</a>
		</li>
		<li class="<?= ($state->direction == 'asc') ? 'active' : ''; ?>">
			<a href="<?= @route($state->direction == 'asc' ? 'direction=' : 'direction=asc' ) ?>">
			    <?= @text('Oldest First') ?>
			</a>
		</li>
		
		<li class="<?= ($state->action == 'add') ? 'active' : ''; ?> separator-left">
			<a href="<?= @route($state->action == 'add' ? 'action=' : 'action=add' ) ?>">
			    <?= @text('Add') ?>
			</a>
		</li>
		<li class="<?= ($state->action == 'edit') ? 'active' : ''; ?>">
			<a href="<?= @route($state->action == 'edit' ? 'action=' : 'action=edit' ) ?>">
			    <?= @text('Edit') ?>
			</a>
		</li>
		<li class="<?= ($state->action == 'delete') ? 'active' : ''; ?>">
			<a href="<?= @route($state->action == 'delete' ? 'action=' : 'action=delete' ) ?>">
			    <?= @text('Delete') ?>
			</a>
		</li>
		<li class="<?= ($state->action == 'login') ? 'active' : ''; ?>">
			<a href="<?= @route($state->action == 'login' ? 'action=' : 'action=login' ) ?>">
			    <?= @text('Login') ?>
			</a>
		</li>
	</ul>
</div>