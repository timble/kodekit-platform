<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<title content="replace"><?= $category->title ?></title>

<h1><?=translate('Search results')?></h1>

<div class="well">
    <form action="<?=route('option=com_articles&view=articles')?>" method="get" class="form-search" style="margin-bottom: 0;">
        <div class="input-append">
        <input id="searchword" name="searchword" class="input-xxlarge search-query" type="text"
               value="<?=escape($state->searchword)?>" placeholder="<?=translate('Search articles')?>"/>
        <button type="submit" class="btn btn-primary"><i class="icon-search icon-white"></i></button>
        </div>
    </form>
</div>

<? foreach ($articles as $article): ?>
    <?= import('default_item.html', array('article' => $article)) ?>
<? endforeach ?>

<? if (count($articles) != $total) : ?>
    <?= helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)) ?>
<? endif ?>