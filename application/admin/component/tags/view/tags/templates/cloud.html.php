<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ktml:style src="assets://tags/css/tags-cloud.css" />

<ul id="tags-tags-cloud">
	<? foreach ($tags as $tag) : ?>
	<? list($component, $view) = explode("_", $tag->table) ?>
	<li>
		<span><?= $tags->count ?> <?= translate('articles are tagged with') ?></span>
		<a  href="<?= route('component='.$component.'&view='.$view.'&tag='.$tag->slug) ?>" class="weight<?= round($tag->count/count(state()) + 1) ?>" ><?= $tag->title; ?></a>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= route('component='.$component.'&view='.$view.'&tag=') ?>"><?= translate('All tags') ?></a>
	</li>
</ul>