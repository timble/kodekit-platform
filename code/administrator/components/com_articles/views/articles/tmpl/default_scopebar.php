<?
/**
 * @version     $Id: default_filter.php 3475 2012-03-17 18:16:38Z tomjanssens $
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
	<div class="scopebar-group">
		<a class="<?= is_null($state->published) && is_null($state->access) && is_null($state->trashed) ? 'active' : ''; ?>" href="<?= @route('published=&access=&trashed=' ) ?>">
		    <?= 'All' ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->published === 1 ? 'active' : ''; ?>" href="<?= @route($state->published === 1 ? 'published=' : 'published=1' ) ?>">
		    <?= 'Published' ?>
		</a>
		<a class="<?= $state->published === 0 ? 'active' : ''; ?>" href="<?= @route($state->published === 0 ? 'published=' : 'published=0' ) ?>">
		    <?= 'Unpublished' ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->access === 1 ? 'active' : ''; ?>" href="<?= @route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
		    <?= 'Registered' ?>
		</a>
	</div>
	<? if($articles->isRevisable()) : ?>
	<div class="scopebar-group <? !$articles->isTranslatable() ? 'last' : '' ?>">
		<a class="<?= $state->trashed ? 'active' : '' ?>" href="<?= @route( $state->trashed ? 'trashed=' : 'trashed=1' ) ?>">
		    <?= 'Trashed' ?>
		</a>
	</div>
	<? endif; ?>
	<? if($articles->isTranslatable()) : ?>
	<div class="scopebar-group last">
	    <a class="<?= $state->translated === false ? 'active' : '' ?>" href="<?= @route($state->translated === false ? 'translated=' : 'translated=0' ) ?>">
		    <?= 'Untranslated' ?>
		</a>
	</div>
	<? endif ?>
	<div class="scopebar-search">
		<?= @helper('grid.search') ?>
	</div>
</div>