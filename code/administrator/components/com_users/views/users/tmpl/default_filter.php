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
		<li class="<?= !is_bool($state->enabled) && $state->visited != '0' && !$state->visited && !$state->loggedin ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('enabled=&visited=&loggedin=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		<li class="<?= $state->enabled === false ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=0' ) ?>">
			    <?= @text('Enabled') ?>
			</a> 
		</li>
		<li class="<?= $state->enabled === true ? 'active' : ''; ?>">
			<a href="<?= @route('enabled=1' ) ?>">
			    <?= @text('Disabled') ?>
			</a> 
		</li>
		<li class="<?= $state->loggedin == '1' ? 'active' : ''; ?> separator-left">
			<a href="<?= @route( $state->loggedin == '1'? 'loggedin=' : 'loggedin=1&visited=1' ) ?>">
			    <?= @text('Logged In Now') ?>
			</a> 
		</li>
		<li class="<?= $state->visited == '0' ? 'active' : ''; ?>">
			<a href="<?= @route( $state->visited == '0'? 'visited=' : 'visited=0' ) ?>">
			    <?= @text('Never Logged In') ?>
			</a> 
		</li>
	</ul>
</div>