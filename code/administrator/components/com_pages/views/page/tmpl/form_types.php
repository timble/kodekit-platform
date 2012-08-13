<?
/**
 * @version     $Id: form_types.php 3030 2011-10-09 13:21:09Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<? $components = @service('com://admin/pages.model.types')->getList() ?>

<div id="components">
    <div class="scrollable">
	    <h3><?= @text('Component') ?></h3>
	    <div id="components-inner">
	        <? foreach($components as $component) : ?>
	        <a data-component="<?= $component->option ?>" class="component-<?= $component->option ?>" href="#"><span class="icon icon-16-component"></span><?= @text($component->name) ?></a>
	        <? endforeach ?>
	    </div>
	    <h3><?= @text('Other') ?></h3>
	    <a href="<?= @route('menu='.$state->menu.'&type[name]=pagelink&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Page link') ?></a>
	    <a href="<?= @route('menu='.$state->menu.'&type[name]=url&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('External link') ?></a>
	    <a href="<?= @route('menu='.$state->menu.'&type[name]=redirect&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Redirect') ?></a>
	    <a href="<?= @route('menu='.$state->menu.'&type[name]=separator&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Separator') ?></a>
    </div>
</div>

<div id="layouts">
    <div id="types" class="scrollable">
	    <? foreach($components as $component) : ?>
	    <div data-component="<?= $component->option ?>" class="component-<?= $component->option ?>">
	        <? foreach($component->views as $view) : ?>
	        <div class="view">
	            <h4><?= @text($view->title) ?></h4>
	            <? foreach($view->layouts as $layout) : ?>
	            <a href="<?= urldecode(@route('menu='.$state->menu.'&type[name]=component&type[option]='.$component->option.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id)) ?>">
	                <?= @text($layout->title) ?>
	                <br />
	                <small><?= @text($layout->description) ?></small>
	            </a>
	            <? endforeach ?>
	        </div>
	        <? endforeach ?>
	    </div>
	    <? endforeach ?>
    </div>
</div>
