<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.keepalive') ?>
<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />
<ktml:script src="assets://pages/js/widget.js" />
<ktml:script src="assets://pages/js/page.js" />

<script>
    window.addEvent('domready', function(){
        $$('.widget').widget({cookie: 'widgets-page'});

        new Pages.Page(<?= json_encode(array('active' => state()->type['name'] == 'component' ? state()->type['component'] : '', 'type' => state()->type['name'])) ?>);
    });
</script>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<? if(state()->type['name'] == 'component') {
    $query = array(
        'component' => state()->type['component'],
        'view'      => state()->type['view']
    );

    if(!empty(state()->type['layout']) && state()->layout != 'default') {
        $query['layout'] = state()->layout;
    }
} ?>

<form action="" method="post" class="-koowa-form" id="page-form">
    <input type="hidden" name="pages_menu_id" value="<?= state()->menu ?>" />
    <input type="hidden" name="type" value="<?= state()->type['name'] ?>" />
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="hidden" value="0" />
    <? if(state()->type['name'] == 'component') : ?>
    <input type="hidden" name="link_url" value="<?= http_build_query($query) ?>" />
    <? endif ?>

    <div id="components">
        <div class="scrollable">
            <h3><?= translate('Component') ?></h3>
            <div id="components-inner">
                <? foreach($components as $component) : ?>
                <? if(!empty($component->views)) : ?>
                <a data-component="<?= $component->name ?>" class="component-<?= $component->name ?> <?= (state()->type['name'] == 'component' && state()->type['component'] == $component->name) ? 'active' : '' ?>" href="#"><span class="icon icon-16-component"></span><?= translate($component->title) ?></a>
                <? endif ?>
                <? endforeach ?>
            </div>
            <? if($menu->application == 'site') : ?>
                <h3><?= translate('Other') ?></h3>
                <a href="<?= route('menu='.state()->menu.'&type[name]=pagelink&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= translate('Page link') ?></a>
                <a href="<?= route('menu='.state()->menu.'&type[name]=url&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= translate('External link') ?></a>
                <a href="<?= route('menu='.state()->menu.'&type[name]=redirect&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= translate('Redirect') ?></a>
                <a href="<?= route('menu='.state()->menu.'&type[name]=separator&id='.$page->id) ?>"><span class="icon icon-16-component"></span><?= translate('Separator') ?></a>
            <? endif ?>
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
                        <h4><?= translate($view->title) ?></h4>
                        <? foreach($view->layouts as $layout) : ?>
                        <a class="<?= (state()->type['name'] == 'component' && state()->type['view'] == $view->name && state()->type['layout'] == $layout->name) ? 'active' : '' ?>" href="<?= urldecode(route('menu='.state()->menu.'&type[name]=component&type[option]='.$component->name.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id)) ?>">
                            <?= translate($layout->title) ?>
                            <br />
                            <small><?= translate($layout->description) ?></small>
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

    <? if(state()->type) : ?>
    <div class="main">
        <div class="title">
            <input class="required" type="text" name="title" placeholder="<?= translate('Title') ?>" value="<?= $page->title ?>" size="50" maxlength="255" />
            <div class="slug">
                <span class="add-on"><?= translate('Slug'); ?></span>
                <input type="text" name="slug" maxlength="255" value="<?= $page->slug ?>" />
            </div>
        </div>
        <div class="tabs tabs-horizontal">
            <div class="tab">
                <input type="radio" id="tab-1" name="tab-group-1" checked="">
                <label for="tab-1"><?= translate('Publish') ?></label>
                <div class="content">
                    <fieldset>
                        <?= import('default_publish.html') ?>
                    </fieldset>
                </div>
            </div>
            <? if($menu->application == 'site' && (state()->type['name'] == 'component' || state()->type['name'] == 'redirect' || state()->type['name'] == 'pagelink')) : ?>
            <div class="tab">
                <input type="radio" id="tab-2" name="tab-group-1">
                <label for="tab-2"><?= translate('Page') ?></label>
                <div class="content">
                    <fieldset>
                        <?= import('default_page.html') ?>
                    </fieldset>
                </div>
            </div>
            <? endif ?>
            <? if($menu->application == 'site' && state()->type['name'] == 'component') : ?>
                <div class="tab">
                    <input type="radio" id="tab-3" name="tab-group-1">
                    <label for="tab-3"><?= translate('Modules') ?></label>
                    <div class="content">
                        <fieldset id="pages-modules">
                            <?= import('default_modules.html') ?>
                        </fieldset>
                    </div>
                </div>
            <? endif ?>
        </div>
    </div>
    <? endif ?>
</form>