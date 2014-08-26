<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ul class="navigation">
    <? foreach($applications as $application) : ?>
        <h4><?= $application ?></h4>
        <? foreach($menus->find(array('application' => $application)) as $menu) : ?>
        <li>
            <a class="<?= state()->menu == $menu->id ? 'active' : '' ?>" href="<?= route('view=pages&menu='.$menu->id ) ?>">
                <span class="navigation__text"><?= escape($menu->title) ?></span>
                <span class="navigation__badge"><?= $menu->page_count ?></span>
            </a>
        </li>
        <? endforeach ?>
    <? endforeach ?>
</ul>
