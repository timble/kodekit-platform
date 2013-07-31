<?
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<ul class="navigation">
    <? foreach($menus as $menu) : ?>
        <li>
            <a data-menu-id="<?=$menu->id;?>"  href="#">
                <?= @escape($menu->title) ?>
            </a>
        </li>

    <? endforeach ?>
</ul>