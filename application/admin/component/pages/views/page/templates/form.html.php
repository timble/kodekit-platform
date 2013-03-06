<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.keepalive') ?>

<!--
<script src="media://koowa/js/koowa.js" />
<script src="media://pages/js/widget.js" />
<script src="media://pages/js/page.js" />
-->

<script>
    window.addEvent('domready', function(){
        $$('.widget').widget({cookie: 'widgets-page'});

        new Pages.Page(<?= json_encode(array('active' => $state->type['name'] == 'component' ? $state->type['option'] : '', 'type' => $state->type['name'])) ?>);
    });
</script>

<?= @template('com://admin/default.view.form.toolbar.html') ?>

<form action="" method="post" class="-koowa-form" id="page-form">
    <input type="hidden" name="pages_menu_id" value="<?= $state->menu ?>" />
    <input type="hidden" name="type" value="<?= $state->type['name'] ?>" />
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="hidden" value="0" />

    <div id="components">
        <div class="scrollable">
            <h3><?= @text('Component') ?></h3>
            <div id="components-inner">
                <? foreach($components as $component) : ?>
                <? if($component->views) : ?>
                <a data-component="<?= $component->name ?>" class="component-<?= $component->name ?> <?= ($state->type['name'] == 'component' && $state->type['option'] == $component->name) ? 'active' : '' ?>" href="#"><span class="icon icon-16-component"></span><?= @text($component->title) ?></a>
                <? endif ?>
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
            <? if($component->views) : ?>
            <div data-component="<?= $component->name ?>" class="component-<?= $component->name ?>">
                <? foreach($component->views as $view) : ?>
                    <? if(count($view->layouts)) : ?>
                    <div class="view">
                        <h4><?= @text($view->title) ?></h4>
                        <? foreach($view->layouts as $layout) : ?>
                        <a class="<?= ($state->type['name'] == 'component' && $state->type['view'] == $view->name && $state->type['layout'] == $layout->name) ? 'active' : '' ?>" href="<?= urldecode(@route('menu='.$state->menu.'&type[name]=component&type[option]='.$component->name.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id)) ?>">
                            <?= @text($layout->title) ?>
                            <br />
                            <small><?= @text($layout->description) ?></small>
                        </a>
                        <? endforeach ?>
                    </div>
                    <? endif ?>
                <? endforeach ?>
            </div>
            <? endif ?>
            <? endforeach ?>
        </div>
    </div>

    <? if($state->type) : ?>
    <div class="main">
        <div class="title">
            <input type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
            <div class="slug">
                <span class="add-on"><?= @text('Slug'); ?></span>
                <input type="text" name="slug" maxlength="255" value="<?= $page->slug ?>" />
            </div>
        </div>
        <div class="scrollable">
            <?= @template('form_publish.html') ?>
            <? if($menu->application == 'site' && ($state->type['name'] == 'component' || $state->type['name'] == 'redirect' || $state->type['name'] == 'pagelink')) : ?>
                <fieldset class="form-horizontal">
                    <legend><?= @text('Page') ?></legend>
                    <?= @template('form_page.html') ?>
                </fieldset>
            <? endif ?>
            <? if($menu->application == 'site' && $state->type['name'] == 'component') : ?>
                <?= @template('form_modules.html') ?>
            <? endif ?>
        </div>
    </div>
    <? endif ?>
</form>
