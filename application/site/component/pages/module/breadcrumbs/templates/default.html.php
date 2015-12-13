<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
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