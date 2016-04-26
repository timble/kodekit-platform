<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ul class="navigation">
    <? foreach($applications as $application) : ?>
        <h4><?= $application ?></h4>
        <? foreach($menus->find(array('application' => $application)) as $menu) : ?>
        <li>
            <a class="<?= parameter('menu') == $menu->id ? 'active' : '' ?>" href="<?= route('view=pages&menu='.$menu->id ) ?>">
                <span class="navigation__text"><?= escape($menu->title) ?></span>
                <span class="navigation__badge"><?= $menu->page_count ?></span>
            </a>
        </li>
        <? endforeach ?>
    <? endforeach ?>
</ul>
