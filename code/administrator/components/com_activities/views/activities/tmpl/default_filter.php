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
		<li class="<?= is_null($state->action) && is_null($state->application) ? 'active' : ''; ?>">
			<a href="<?= @route('application=&action=' ) ?>">
			    <?= @text('All') ?>
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
	</ul>
</div>