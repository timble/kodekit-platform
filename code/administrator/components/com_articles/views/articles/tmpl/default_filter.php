<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= is_null($state->state) && is_null($state->access) && is_null($state->featured) && is_null($state->deleted) ? 'active' : ''; ?> separator-right">
			<a href="<?= @route('state=&access=&featured=&trashed=' ) ?>">
			    <?= 'All' ?>
			</a>
		</li>
		<li class="<?= $state->state === 0 ? 'active' : ''; ?>">
			<a href="<?= @route($state->state === 0 ? 'state=' : 'state=0' ) ?>">
			    <?= 'Draft' ?>
			</a>
		</li>
		<li class="<?= $state->state === 1 ? 'active' : ''; ?>">
			<a href="<?= @route($state->state === 1 ? 'state=' : 'state=1' ) ?>">
			    <?= 'Published' ?>
			</a>
		</li>
		<li class="<?= $state->state === -1 ? 'active' : ''; ?>">
			<a href="<?= @route($state->state === -1 ? 'state=' : 'state=-1' ) ?>">
			    <?= 'Archived' ?>
			</a>
		</li>
		<li class="<?= $state->access === 0 ? 'active' : ''; ?> separator-left">
			<a href="<?= @route($state->access === 0 ? 'access=' : 'access=0' ) ?>">
			    <?= 'Public' ?>
			</a>
		</li>
		<li class="<?= $state->access === 1 ? 'active' : ''; ?>">
			<a href="<?= @route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
			    <?= 'Registered' ?>
			</a>
		</li>
		<li class="<?= $state->access === 2 ? 'active' : ''; ?>">
			<a href="<?= @route($state->access === 2 ? 'access=' : 'access=2' ) ?>">
			    <?= 'Special' ?>
			</a>
		</li>
		<li class="<?= $state->featured ? 'active' : ''; ?> separator-left">
			<a href="<?= @route( $state->featured ? 'featured=' : 'featured=1' ) ?>">
			    <?= 'Featured' ?>
			</a>
		</li>
		<? if($articles->isRevisable()) : ?>
		<li class="<?= $state->trashed ? 'active' : '' ?>  separator-left"">
			<a href="<?= @route( $state->trashed ? 'trashed=' : 'trashed=1' ) ?>">
			    <?= 'Trashed' ?>
			</a>
		</li>
		<? endif; ?>
	</ul>
</div>