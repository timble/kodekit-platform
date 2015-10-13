<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? if(parameters()->show_title) : ?>
    <h3><?= title() ?></h3>
<? endif ?>

<ul>
    <? foreach ($articles as $article): ?>
    <li>
        <a href="<?= helper('com:articles.route.article', array('entity' => $article)) ?>"><?= escape($article->title) ?></a>
    </li>
    <? endforeach; ?>
</ul>