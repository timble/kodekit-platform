<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

    <title content="replace"><?= $category->title ?></title>

    <h1><?= translate('Search results') ?></h1>

    <div class="well">
        <form action="<?= route('component=articles&view=articles') ?>" method="get" class="form-search"
              style="margin-bottom: 0;">
            <div class="form-group">
                <input id="search" name="search" class="form-control" type="text"
                       value="<?= escape(parameter('search')) ?>" placeholder="<?= translate('Search articles') ?>"/>
                <button type="submit" class="btn btn-primary"><?= translate('Submit') ?></button>
            </div>
        </form>
    </div>

<? foreach ($articles as $article): ?>
    <?= import('default_item.html', array('article' => $article)) ?>
<? endforeach ?>

<? if (count($articles) != parameter('total')) : ?>
    <?= helper('paginator.pagination', array('total' => parameter('total'), 'show_limit' => false, 'show_count' => false)) ?>
<? endif ?>