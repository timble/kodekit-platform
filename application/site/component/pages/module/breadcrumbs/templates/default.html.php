<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<ul class="breadcrumb">
	<? foreach($list as $item) : ?>
		<? // If not the last item in the breadcrumbs add the separator ?>
        <? if($item !== end($list)) : ?>
			<? if(!empty($item->link)) : ?>
				<li><a href="<?= $item->link ?>" class="pathway"><?= @escape($item->name) ?></a></li>
			<? else : ?>
				<li><?= @escape($item->name) ?></li>
			<? endif ?>
			<span class="divider">&rsaquo;</span>
		<? else : ?>
		    <li><?= @escape($item->name) ?></li>
		<? endif ?>
	<? endforeach ?>
</ul>