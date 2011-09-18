<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= !is_bool($state->enabled) && !$state->deleted ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('enabled=&deleted=' ) ?>">
			    <?= 'All' ?>
			</a>
		</li>
		<li class="<?= $state->enabled === true ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=1' ) ?>">
			    <?= @text('Published') ?>
			</a> 
		</li>
		<li class="<?= $state->enabled === false ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=0' ) ?>">
			    <?= @text('Unpublished') ?>
			</a> 
		</li>
		<li class="<?= $state->deleted ? 'active' : '' ?> separator-left">
			<a href="<?= @route( $state->deleted ? 'deleted=' : 'deleted=1' ) ?>">
			    <?= 'Trashed' ?>
			</a>
		</li>
	</ul>
</div>