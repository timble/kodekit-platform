<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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

        new Pages.Page(<?= json_encode(array('active' => parameters()->type['component'], 'type' => parameters()->type['name'])) ?>);
    });
</script>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>
<?
    $query = array(
        'component' => parameters()->type['component'],
        'view'      => parameters()->type['view']
    );

    if(!empty(parameters()->type['layout']) && parameters()->layout != 'default') {
        $query['layout'] = parameters()->layout;
    }
?>

<form action="" method="post" class="-koowa-form" id="page-form">
    <input type="hidden" name="pages_menu_id" value="<?= parameters()->menu ?>" />
    <input type="hidden" name="type" value="<?= parameters()->type['name'] ?>" />
    <input type="hidden" name="access" value="0" />
    <input type="hidden" name="published" value="0" />
    <input type="hidden" name="hidden" value="0" />
    <input type="hidden" name="state" value="<?= http_build_query($query) ?>" />

    <div id="components">
        <div class="scrollable">
            <h3><?= translate('Component') ?></h3>
            <div id="components-inner">
                <? foreach($components as $component) : ?>
                    <? if(!empty($component->views)) : ?>
                        <a data-component="<?= $component->name ?>" class="component-<?= $component->name ?> <?= (parameters()->type['component'] == $component->name) ? 'active' : '' ?>" href="#"><span class="icon icon-16-component"></span><?= translate($component->title) ?></a>
                    <? endif ?>
                <? endforeach ?>
            </div>
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
                        <a class="<?= (parameters()->type['view'] == $view->name && parameters()->type['layout'] == $layout->name) ? 'active' : '' ?>" href="<?= urldecode(route('menu='.$menu->id.'&type[component]='.$component->name.'&type[view]='.$view->name.'&type[layout]='.$layout->name.'&id='.$page->id)) ?>">
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
            <? if($menu->application == 'site') : ?>
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
            <? if($menu->application == 'site') : ?>
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
</form>