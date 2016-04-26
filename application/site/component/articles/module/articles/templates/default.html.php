<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? if(parameter('show_title')) : ?>
    <h3><?= title() ?></h3>
<? endif ?>

<ul>
    <? foreach ($articles as $article): ?>
    <li>
        <a href="<?= helper('com:articles.route.article', array('entity' => $article)) ?>"><?= escape($article->title) ?></a>
    </li>
    <? endforeach; ?>
</ul>