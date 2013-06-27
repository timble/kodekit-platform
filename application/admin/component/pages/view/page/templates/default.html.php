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
<?= @helper('behavior.validator') ?>

<!--
<script src="media://js/koowa.js" />
<script src="media://pages/js/widget.js" />
<script src="media://pages/js/page.js" />
-->

<script>
    window.addEvent('domready', function(){
        $$('.widget').widget({cookie: 'widgets-page'});

        new Pages.Page(<?= json_encode(array('active' => $state->type['name'] == 'component' ? $state->type['option'] : '', 'type' => $state->type['name'])) ?>);
    });
</script>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<? if($state->type['name'] == 'component') {
    $query = array(
        'option' => $state->type['option'],
        'view'   => $state->type['view']
    );

    if(!empty($state->type['layout']) && $state->layout != 'default') {
        $query['layout'] = $state->layout;
    }
} ?>

<form action="" method="post" class="-koowa-form" id="page-form">
    <input type="hidden" name="pages_menu_id" value="<?= $state->menu ?>" />
    <input type="hidden" name="type" value="<?= $state->type['name'] ?>" />
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="hidden" value="0" />
    <? if($state->type['name'] == 'component') : ?>
    <input type="hidden" name="link_url" value="<?= http_build_query($query) ?>" />
    <? endif ?>

    <div id="components">
        <div class="scrollable">
            <h3><?= @text('Component') ?></h3>
            <div id="components-inner">
                <? foreach($extensions as $extension) : ?>
                <? if($extension->views) : ?>
                <a data-component="<?= $extension->name ?>" class="component-<?= $extension->name ?> <?= ($state->type['name'] == 'component' && $state->type['option'] == $extension->name) ? 'active' : '' ?>" href="#"><span class="icon icon-16-component"></span><?= @text($extension->title) ?></a>
                <? endif ?>
                <? endforeach ?>
            </div>
            <? if($menu->application == 'site') : ?>
                <h3><?= @text('Other') ?></h3>
                <a href="<?= @route('menu='.$state->menu.'&type[name]=pagelink&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Page link') ?></a>
                <a href="<?= @route('menu='.$state->menu.'&type[name]=url&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('External link') ?></a>
                <a href="<?= @route('menu='.$state->menu.'&type[name]=redirect&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Redirect') ?></a>
                <a href="<?= @route('menu='.$state->menu.'&type[name]=separator&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= @text('Separator') ?></a>
            <? endif ?>
        </div>
    </div>
    
    <div id="layouts">
        <div id="types" class="scrollable">
            <? foreach($extensions as $extension) : ?>
            <? if($extension->views) : ?>
            <div data-component="<?= $extension->name ?>" class="extension-<?= $extension->name ?>">
                <? foreach($extension->views as $view) : ?>
                    <? if(count($view->layouts)) : ?>
                    <div class="view">
                        <h4><?= @text($view->title) ?></h4>
                        <? foreach($view->layouts as $layout) : ?>
                        <a class="<?= ($state->type['name'] == 'extension' && $state->type['view'] == $view->name && $state->type['layout'] == $layout->name) ? 'active' : '' ?>" href="<?= urldecode(@route('menu='.$state->menu.'&type[name]=component&type[option]='.$extension->name.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id)) ?>">
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
            <input class="required" type="text" name="title" placeholder="<?= @text('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
            <div class="slug">
                <span class="add-on"><?= @text('Slug'); ?></span>
                <input type="text" name="slug" maxlength="255" value="<?= $page->slug ?>" />
            </div>
        </div>
        <div class="tabs tabs-horizontal">
            <div class="tab">
                <input type="radio" id="tab-1" name="tab-group-1" checked="">
                <label for="tab-1"><?= @text('Publish') ?></label>
                <div class="content">
                    <fieldset>
                        <?= @template('default_publish.html') ?>
                    </fieldset>
                </div>
            </div>
            <? if($menu->application == 'site' && ($state->type['name'] == 'component' || $state->type['name'] == 'redirect' || $state->type['name'] == 'pagelink')) : ?>
            <div class="tab">
                <input type="radio" id="tab-2" name="tab-group-1">
                <label for="tab-2"><?= @text('Page') ?></label>
                <div class="content">
                    <fieldset>
                        <?= @template('default_page.html') ?>
                    </fieldset>
                </div>
            </div>
            <? endif ?>
            <? if($menu->application == 'site' && $state->type['name'] == 'component') : ?>
                <div class="tab">
                    <input type="radio" id="tab-3" name="tab-group-1">
                    <label for="tab-3"><?= @text('Modules') ?></label>
                    <div class="content">
                        <fieldset id="pages-modules">
                            <?= @template('default_modules.html') ?>
                        </fieldset>
                    </div>
                </div>
            <? endif ?>
        </div>
    </div>
    <? endif ?>
</form>