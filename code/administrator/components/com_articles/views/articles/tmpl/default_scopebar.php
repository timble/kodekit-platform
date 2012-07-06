<?php
/**
 * @version     $Id: default_filter.php 3475 2012-03-17 18:16:38Z tomjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div class="scopebar">
	<div class="scopebar-group">
		<a class="<?= is_null($state->state) && is_null($state->access) && is_null($state->featured) && is_null($state->trashed) ? 'active' : ''; ?>" href="<?= @route('state=&access=&featured=&trashed=' ) ?>">
		    <?= 'All' ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->state === 0 ? 'active' : ''; ?>" href="<?= @route($state->state === 0 ? 'state=' : 'state=0' ) ?>">
		    <?= 'Draft' ?>
		</a>
		<a class="<?= $state->state === 1 ? 'active' : ''; ?>" href="<?= @route($state->state === 1 ? 'state=' : 'state=1' ) ?>">
		    <?= 'Published' ?>
		</a>
		<a class="<?= $state->state === -1 ? 'active' : ''; ?>" href="<?= @route($state->state === -1 ? 'state=' : 'state=-1' ) ?>">
		    <?= 'Archived' ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->access === 0 ? 'active' : ''; ?>" href="<?= @route($state->access === 0 ? 'access=' : 'access=0' ) ?>">
		    <?= 'Public' ?>
		</a>
		<a class="<?= $state->access === 1 ? 'active' : ''; ?>" href="<?= @route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
		    <?= 'Registered' ?>
		</a>
		<a class="<?= $state->access === 2 ? 'active' : ''; ?>" href="<?= @route($state->access === 2 ? 'access=' : 'access=2' ) ?>">
		    <?= 'Special' ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->featured ? 'active' : ''; ?>" href="<?= @route( $state->featured ? 'featured=' : 'featured=1' ) ?>">
		    <?= 'Featured' ?>
		</a>
	</div>
	<? if($articles->isRevisable()) : ?>
	<div class="scopebar-group last">
		<a class="<?= $state->trashed ? 'active' : '' ?>" href="<?= @route( $state->trashed ? 'trashed=' : 'trashed=1' ) ?>">
		    <?= 'Trashed' ?>
		</a>
	</div>
	<? endif; ?>
	<div class="scopebar-search">
		<?= @helper('grid.search') ?>
	</div>
</div>