<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

    <title content="replace"><?= $category->title ?></title>

    <h1><?= translate('Search results') ?></h1>

    <div class="well">
        <form action="<?= route('component=articles&view=articles') ?>" method="get" class="form-search"
              style="margin-bottom: 0;">
            <div class="form-group">
                <input id="search" name="search" class="form-control" type="text"
                       value="<?= escape(state()->search) ?>" placeholder="<?= translate('Search articles') ?>"/>
                <button type="submit" class="btn btn-primary"><?= translate('Submit') ?></button>
            </div>
        </form>
    </div>

<? foreach ($articles as $article): ?>
    <?= import('default_item.html', array('article' => $article)) ?>
<? endforeach ?>

<? if (count($articles) != count(state())) : ?>
    <?= helper('paginator.pagination', array('total' => count(state()), 'show_limit' => false, 'show_count' => false)) ?>
<? endif ?>