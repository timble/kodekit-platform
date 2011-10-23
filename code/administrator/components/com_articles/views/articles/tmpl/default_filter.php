<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= !is_numeric($state->state) && !$state->featured && !$state->deleted ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('state=&featured=&trashed=' ) ?>">
			    <?= 'All' ?>
			</a>
		</li>
		<li class="<?= $state->state == '0' ? 'active' : ''; ?>">
			<a href="<?= @route('state=0' ) ?>">
			    <?= 'Draft' ?>
			</a>
		</li>
		<li class="<?= $state->state == '1' ? 'active' : ''; ?>">
			<a href="<?= @route('state=1' ) ?>">
			    <?= 'Published' ?>
			</a>
		</li>
		<li class="<?= $state->state == '-1' ? 'active' : ''; ?>">
			<a href="<?= @route('state=-1' ) ?>">
			    <?= 'Archived' ?>
			</a>
		</li>
		<li class="<?= $state->featured ? 'active' : ''; ?> separator-left">
			<a href="<?= @route( $state->featured ? 'featured=' : 'featured=1' ) ?>">
			    <?= 'Featured' ?>
			</a>
		</li>
		<? if($articles->isRevisable()) : ?>
		<li class="<?= $state->trashed ? 'active' : '' ?>">
			<a href="<?= @route( $state->trashed ? 'trashed=' : 'trashed=1' ) ?>">
			    <?= 'Trashed' ?>
			</a>
		</li>
		<? endif; ?>
	</ul>
</div>