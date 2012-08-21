<?
/**
 * @version		$Id: form.php 1294 2011-05-16 22:57:57Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<? $first = true; $last_depth = 0; ?>
<? foreach($pages as $page) : ?>
    <? $depth = substr_count($page->path, '/') ?>
    
    <? if(substr($page->path, -1) != '/') : ?>
        <? $depth++ ?>
    <? endif ?>

    <? if($depth > $last_depth) : ?>
        <ul <?= $first ? 'class="nav"' : '' ?>>
        <? $last_depth = $depth; $first = false; ?>
    <? endif ?>

    <? if($depth < $last_depth) : ?>
        <?= str_repeat('</li></ul>', $last_depth - $depth) ?>
        <? $last_depth = $depth ?>
    <? endif ?>
    
    <? if($depth == $last_depth) : ?>
        </li>
    <? endif ?>
    
    <li <?= $page->id == $active->id ? 'class="active"' : '' ?>>
        <? switch($page->type) : 
              case 'component': ?>
				<a href="<?= JRoute::_($page->link.'&Itemid='.$page->id) ?>">
                    <span><?= $page->title ?></span>
                </a>
				<? break ?>
				
		    <? case 'menulink': ?>
		        <? $page_linked = @service('application')->getPages()->find($page->link->query['Itemid']); ?>
		        <a href="<?= $page_linked->link ?>">
                    <span><?= $page->title ?></span>
                </a>
				<? break ?>
				
            <? case 'separator': ?>
				<span class="separator"><span><?= $page->title ?></span></span>
				<? break ?>

			<? case 'url': ?>
				<a href="<?= $page->link ?>">
                    <span><?= $page->title ?></span>
                </a>
				<? break ?>
				
	        <? case 'redirect'?>
	            <a href="<?= $page->route ?>">
	                <span><?= $page->title ?></span>
	            </a>
		<? endswitch ?>
<? endforeach ?>