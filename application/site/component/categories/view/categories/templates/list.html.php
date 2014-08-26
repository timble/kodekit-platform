<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ul>
    <? foreach ($categories as $category): ?>
    <li<?= $category->id == state()->category ? ' class="active"' : '' ?>>
        <a href="<?= helper('route.category', array('entity' => $category)) ?>">
            <?= $category->title ?>
        </a>
    </li>
    <? endforeach ?>
</ul>