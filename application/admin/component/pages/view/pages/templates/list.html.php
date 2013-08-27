<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://pages/js/pages-list.js" />

<ul class="navigation">
    <? foreach(object('com:pages.model.menus')->sort('title')->application('site')->getRowset() as $menu) : ?>
        <? $menu_pages = object('com:pages.model.pages')->getRowset()->find(array('pages_menu_id' => $menu->id)) ?>
        <? if(count($menu_pages)) : ?>
            <h3><?= $menu->title ?></h3>
            <? $first = true; $last_depth = 0; ?>

            <? foreach($menu_pages as $page) : ?>
                <li>
                <? $depth = substr_count($page->path, '/') ?>
                <? switch($page->type) :
                    case 'component': ?>
                        <a class="level<?= $depth ?>" href="<?= route(preg_replace('%layout=table%', 'layout=default', $page->getLink()->getQuery()).'&Itemid='.$page->id) ?>">
                            <span><?= $page->title ?></span>
                        </a>
                        <? break ?>

                    <? case 'menulink': ?>
                        <? $page_linked = object('application.pages')->getPage($page->getLink()->query['Itemid']); ?>
                        <a href="<?= $page_linked->getLink() ?>">
                            <span><?= $page->title ?></span>
                        </a>
                        <? break ?>

                    <? case 'separator': ?>
                        <span class="separator"><span><?= $page->title ?></span></span>
                        <? break ?>

                    <? case 'url': ?>
                        <a href="<?= $page->getLink() ?>">
                            <span><?= $page->title ?></span>
                        </a>
                        <? break ?>

                    <? case 'redirect': ?>
                        <a href="<?= $page->route ?>">
                            <span><?= $page->title ?></span>
                        </a>
                    <? endswitch ?>
                </li>
            <? endforeach ?>
        <? endif; ?>
    <? endforeach ?>
</ul>