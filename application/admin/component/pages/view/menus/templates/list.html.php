<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<nav class="scrollable">
    <? foreach($applications as $application) : ?>
        <h4><?= $application ?></h4>
        <? foreach($menus->find(array('application' => $application)) as $menu) : ?>
            <a class="<?= $state->menu == $menu->id ? 'active' : '' ?>" href="<?= @route('view=pages&menu='.$menu->id ) ?>">
                <?= @escape($menu->title) ?> <span class="badge"><?= $menu->page_count ?></span>
            </a>
        <? endforeach ?>
    <? endforeach ?>
</nav>
