<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? if(parameters()->show_title) : ?>
    <h3><?= title() ?></h3>
<? endif ?>

<ul class="breadcrumb">
	<? foreach($pathway as $item) : ?>
		<? // If not the last item in the breadcrumbs add the separator ?>
        <? if($item !== end($pathway)) : ?>
			<? if(isset($item['link'])) : ?>
				<li><a href="<?= route((string) $item['link']) ?>" class="pathway"><?= escape($item['title']) ?></a></li>
			<? else : ?>
				<li><?= escape($item['title']) ?></li>
			<? endif ?>
			<span class="divider">&rsaquo;</span>
		<? else : ?>
		    <li><?= escape($item['title']) ?></li>
		<? endif ?>
	<? endforeach ?>
</ul>