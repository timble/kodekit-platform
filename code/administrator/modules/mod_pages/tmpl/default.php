<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<ul id="menu">
    <li class="<?= $disabled ? 'disabled' : '' ?>"><a <?= !$disabled ? 'href="'.@route('option=com_dashboard&view=dashboard').'"' : '' ?>><?= @text('Dashboard') ?></a></li>
    <? if($user->authorize('com_menus', 'manage')) : ?> 
        <li class="<?= $disabled ? 'disabled' : '' ?>"><a <?= !$disabled ? 'href="'.@route('option=com_pages&view=pages').'"' : '' ?>><?= @text('Pages') ?></a></li>
    <? endif ?>
    
    <? if($user->authorize('com_components', 'manage')) : ?>
        <li class="<?= $disabled ? 'disabled' : 'node' ?>">
            <a><?= @text('Components') ?></a>
            <? if(!$disabled) : ?>
                <ul>
                    <? foreach($components as $component) : ?>
                        <? if($component['data']->parent == 0 && (trim($component['data']->admin_menu_link) || $component['children'])) : ?>
                            <li class="<?= $component['children'] ? 'node' : '' ?>">
                                <a href="<?= @route($component['data']->admin_menu_link) ?>"><?= $component['data']->name ?></a>
                                <? if($component['children']) : ?>
                                    <ul>
                                        <? foreach($component['children'] as $child) : ?>
                                            <li><a href="<?= @route($child['data']->admin_menu_link) ?>"><?= $child['data']->name ?></a></li>
                                        <? endforeach ?>
                                    </ul>
                                <? endif ?>
                            </li>
                        <? endif ?>
                    <? endforeach ?>
                </ul>
            <? endif ?>
        </li>
    <? endif ?>
    
    <li class="<?= $disabled ? 'disabled' : '' ?>"><a <?= !$disabled ? 'href="'.@route('option=com_files&view=files').'"' : '' ?>><?= @text('Files') ?></a></li>
    <? if($user->authorize('com_users', 'manage')) : ?>
        <li class="<?= $disabled ? 'disabled' : '' ?>"><a <?= !$disabled ? 'href="'.@route('option=com_users&view=users').'"' : '' ?>><?= @text('Users') ?></a></li> 
    <? endif ?>
    
    <? if($user->authorize('com_installer', 'module')) : ?>
        <li class="<?= $disabled ? 'disabled' : 'node' ?>">
            <a><?= @text('Extensions') ?></a>
            <? if(!$disabled) : ?>
                <ul>
                    <? if($user->authorize('com_modules', 'manage')) : ?>
                        <li><a href="<?= @route('option=com_extensions&view=modules') ?>"><?= @text('Modules') ?></a></li>
                    <? endif ?>
                    <? if($user->authorize('com_languages', 'manage')) : ?>
                        <li><a href="<?= @route('option=com_extensions&view=languages') ?>"><?= @text('Languages') ?></a></li>
                    <? endif ?>
                </ul>
            <? endif ?>
        </li>
    <? endif ?>
    
    <? if($user->authorize('com_settings', 'manage')) : ?>
        <li class="<?= $disabled ? 'disabled' : 'node' ?>">
            <a><?= @text('Tools') ?></a>
            <? if(!$disabled) : ?>
                <ul>
                    <li><a href="<?= @route('option=com_settings&view=settings') ?>"><?= @text('Settings') ?></a></li>
                    <li class="separator"><span></span></li>
                    <li><a href="<?= @route('option=com_activities&view=activities') ?>"><?= @text('Activity Logs') ?></a></li>
                    <li class="separator"><span></span></li>
                    <li><a href="<?= @route('option=com_cache&view=items') ?>"><?= @text('Clean Cache') ?></a></li>
                </ul>
            <? endif ?>
        </li>
    <? endif ?>
</ul>