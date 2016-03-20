<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ul class="navigation">
	<li>
        <a class="<?= parameters()->category == null ? 'active' : ''; ?>" href="<?= route('category=' ) ?>">
            <?= translate('All categories') ?>
        </a>
	</li>
	<? foreach ($categories as $category) : ?>
	<li>
        <a class="<?= parameters()->category == $category->id ? 'active' : ''; ?>" href="<?= route('category='.$category->id ) ?>">
            <?= escape($category->title) ?>
        </a>
	</li>
	<? endforeach ?>
</ul>