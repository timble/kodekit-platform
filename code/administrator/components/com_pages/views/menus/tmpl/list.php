<?php
/**
 * @version     $Id: list.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<ul class="scrollable">
    <? foreach($menus as $menu) : ?>
    <li class="<?= $state->menu == $menu->id ? 'active' : '' ?>">
        <a href="<?= @route('view=pages&menu='.$menu->id ) ?>">
            <?= @escape($menu->title) ?> (<?= $menu->page_count; ?>)
        </a>
    </li>
    <? endforeach ?>
</ul>
