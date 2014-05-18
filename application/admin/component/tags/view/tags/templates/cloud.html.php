<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<style src="assets://tags/css/tags-cloud.css" />

<ul id="tags-tags-cloud">
	<? foreach ($tags as $tag) : ?>
	<? list($extension, $view) = explode("_", $tag->table) ?>
	<li>
		<span><?= $tags->count ?> <?= translate('articles are tagged with') ?></span>
		<a  href="<?= route('option=com_'.$extension.'&view='.$view.'&tag='.$tag->slug) ?>" class="weight<?= round($tag->count/$total + 1) ?>" ><?= $tag->title; ?></a>
	</li>
	<? endforeach; ?>
	<li>
		<a href="<?= route('option=com_'.$extension.'&view='.$view.'&tag=') ?>"><?= translate('All tags') ?></a>
	</li>
</ul>