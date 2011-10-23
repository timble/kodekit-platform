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
		<li class="<?= !is_bool($state->published) && !is_numeric($state->sticky) ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('published=&sticky=' ) ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		<li class="<?= $state->published === true? 'active' : ''; ?>">
			<a href="<?= @route($state->published === true? 'published=' : 'published=1') ?>">
			    <?= @text('Published') ?>
			</a> 
		</li>
		<li class="<?= $state->published === false? 'active' : ''; ?>">
			<a href="<?= @route($state->published === false? 'published=' : 'published=0' ) ?>">
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