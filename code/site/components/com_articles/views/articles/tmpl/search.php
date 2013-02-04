<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>

<h1><?=@text('Search results')?></h1>

<div class="well">
    <form action="<?=@route('option=com_articles&view=articles')?>" method="get" class="form-search">
        <input id="searchword" name="searchword" class="input-xxlarge search-query" type="text"
               value="<?=@escape($state->searchword)?>" placeholder="<?=@text('Search articles')?>"/>
        <button type="submit" class="btn"><?=@text('Search')?></button>
    </form>
</div>

<? foreach ($articles as $article): ?>
    <?= @template('default_item', array('article' => $article)) ?>
<? endforeach ?>

<? if (count($articles) != $total) : ?>
    <?= @helper('paginator.pagination', array('total' => $total, 'show_limit' => false, 'show_count' => false)) ?>
<? endif ?>