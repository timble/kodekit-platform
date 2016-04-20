<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:style src="assets://articles/css/tags-cloud.css" />

<ul id="tags-tags-cloud">
	<? foreach ($tags as $tag) : ?>
	<? list($component, $view) = explode("_", $tag->table) ?>
	<li>
		<span><?= $tags->count ?> <?= translate('articles are tagged with') ?></span>
		<a  href="<?= route('component='.$component.'&view='.$view.'&tag='.$tag->slug) ?>" class="weight<?= round($tag->count/parameters()->total + 1) ?>" ><?= $tag->title; ?></a>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= route('component='.$component.'&view='.$view.'&tag=') ?>"><?= translate('All tags') ?></a>
	</li>
</ul>