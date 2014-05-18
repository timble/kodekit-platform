<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null($state->published) && is_null($state->access) && is_null($state->trashed) ? 'active' : ''; ?>" href="<?= route('published=&access=&trashed=' ) ?>">
		    <?= 'All' ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->published === 1 ? 'active' : ''; ?>" href="<?= route($state->published === 1 ? 'published=' : 'published=1' ) ?>">
		    <?= 'Published' ?>
		</a>
		<a class="<?= $state->published === 0 ? 'active' : ''; ?>" href="<?= route($state->published === 0 ? 'published=' : 'published=0' ) ?>">
		    <?= 'Unpublished' ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->access === 1 ? 'active' : ''; ?>" href="<?= route($state->access === 1 ? 'access=' : 'access=1' ) ?>">
		    <?= 'Registered' ?>
		</a>
	</div>
	<? if($articles->isRevisable()) : ?>
	<div class="scopebar__group <? !$articles->isTranslatable() ? 'last' : '' ?>">
		<a class="<?= $state->trashed ? 'active' : '' ?>" href="<?= route( $state->trashed ? 'trashed=' : 'trashed=1' ) ?>">
		    <?= 'Trashed' ?>
		</a>
	</div>
	<? endif; ?>
	<? if($articles->isTranslatable()) : ?>
	<div class="scopebar__group">
	    <a class="<?= $state->translated === false ? 'active' : '' ?>" href="<?= route($state->translated === false ? 'translated=' : 'translated=0' ) ?>">
		    <?= 'Untranslated' ?>
		</a>
	</div>
	<? endif ?>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>