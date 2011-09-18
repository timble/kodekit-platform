<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= !is_bool($state->enabled) && !is_numeric($state->sticky) ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('enabled=&sticky=' ) ?>">
			    <?= @text('All') ?>
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
		<li class="<?= $state->sticky ? 'active' : ''; ?> separator-left">
			<a href="<?= @route( $state->sticky ? 'sticky=' : 'sticky=1' ) ?>">
			    <?= @text('Sticky') ?>
			</a> 
		</li>
	</ul>
</div>