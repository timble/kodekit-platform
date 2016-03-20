<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null(parameters()->published) && is_null(parameters()->access) && is_null(parameters()->trashed) ? 'active' : ''; ?>" href="<?= route('published=&access=&trashed=' ) ?>">
		    <?= 'All' ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameters()->published === 1 ? 'active' : ''; ?>" href="<?= route(parameters()->published === 1 ? 'published=' : 'published=1' ) ?>">
		    <?= 'Published' ?>
		</a>
		<a class="<?= parameters()->published === 0 ? 'active' : ''; ?>" href="<?= route(parameters()->published === 0 ? 'published=' : 'published=0' ) ?>">
		    <?= 'Unpublished' ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameters()->access === 1 ? 'active' : ''; ?>" href="<?= route(parameters()->access === 1 ? 'access=' : 'access=1' ) ?>">
		    <?= 'Registered' ?>
		</a>
	</div>
	<? if($articles->isRevisable()) : ?>
	<div class="scopebar__group <? !$articles->isTranslatable() ? 'last' : '' ?>">
		<a class="<?= parameters()->trashed ? 'active' : '' ?>" href="<?= route( parameters()->trashed ? 'trashed=' : 'trashed=1' ) ?>">
		    <?= 'Trashed' ?>
		</a>
	</div>
	<? endif; ?>
	<? if($articles->isTranslatable()) : ?>
	<div class="scopebar__group">
	    <a class="<?= parameters()->translated === false ? 'active' : '' ?>" href="<?= route(parameters()->translated === false ? 'translated=' : 'translated=0' ) ?>">
		    <?= 'Untranslated' ?>
		</a>
	</div>
	<? endif ?>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>