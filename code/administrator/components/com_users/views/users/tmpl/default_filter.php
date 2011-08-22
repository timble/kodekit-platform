<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= !is_numeric($state->enabled) && !$state->never_visited && !$state->loggedin ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('enabled=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		<li class="<?= $state->enabled == '0' ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=0' ) ?>">
			    <?= @text('Enabled') ?>
			</a> 
		</li>
		<li class="<?= $state->enabled == '1' ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=1' ) ?>">
			    <?= @text('Disabled') ?>
			</a> 
		</li>
		<li class="<?= $state->loggedin == '1' ? 'active' : ''; ?> separator-left">
			<a href="<?= @route( $state->loggedin == '1'? 'loggedin=' : 'loggedin=1' ) ?>">
			    <?= @text('Logged in') ?>
			</a> 
		</li>
		<li class="<?= $state->never_visited == '1' ? 'active' : ''; ?>">
			<a href="<?= @route( $state->never_visited == '1'? 'never_visited=' : 'never_visited=1' ) ?>">
			    <?= @text('Never visited') ?>
			</a> 
		</li>
	</ul>
</div>