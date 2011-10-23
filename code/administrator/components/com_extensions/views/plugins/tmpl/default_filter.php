<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= is_null($state->enabled) ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('enabled=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		<li class="<?= $state->enabled === true ? 'active' : ''; ?>">
			<a href="<?= @route($state->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
			    <?= @text('Enabled') ?>
			</a> 
		</li>
		<li class="<?= $state->enabled === false ? 'active' : ''; ?>">
			<a href="<?= @route($state->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
			    <?= @text('Disabled') ?>
			</a> 
		</li>
	</ul>
</div>