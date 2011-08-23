<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<? $components = KFactory::get('admin::com.pages.model.types')->getList() ?>

<div id="components" class="scroll">
	<h3><?= @text('Component') ?></h3>
	<div id="components-inner">
		<? foreach($components as $component) : ?>
		<span><a data-component="<?= $component->name ?>" class="component-<?= $component->name ?>" href="#"><span class="icon icon-16-component"></span><?= @text($component->title) ?></a></span>
		<? endforeach ?>
	</div>
	<h3><?= @text('Other') ?></h3>
	<a href="<?= @route('menu='.$state->menu.'&type[name]=url&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('External link') ?></a>
	<a href="<?= @route('menu='.$state->menu.'&type[name]=separator&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Separator') ?></a>
	<a href="<?= @route('menu='.$state->menu.'&type[name]=menulink&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Alias') ?></a>
</div>

<div id="types" class="scroll">
	<? foreach($components as $component) : ?>
	<div data-component="<?= $component->name ?>" class="component-<?= $component->name ?>">
		<? foreach($component->views as $view) : ?>
		<div class="view">
			<h4><?= @text($view->title) ?></h4>
			<? foreach($view->layouts as $layout) : ?>
			<a href="<?= @route('menu='.$state->menu.'&type[name]=component&type[option]='.$component->name.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id) ?>">
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