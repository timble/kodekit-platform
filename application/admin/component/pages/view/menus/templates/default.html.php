<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<ktml:block prepend="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:block>

<form id="menus-form" action="<?= route() ?>" method="get" class="-koowa-grid">
    <table>
        <thead>
            <tr>
                <th width="1">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($menus); ?>);" />
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'title' , 'title' => 'Title')); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="4">
                    <?= helper('com:theme.paginator.pagination') ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <? foreach($menus as $menu) : ?>
            <tr>
                <td align="center">
                    <?= helper('grid.checkbox',array('entity' => $menu)); ?>
                </td>
                <td>
                    <? if(!parameter('trash')) : ?>
                    <a href="<?= route('view=menu&id='.$menu->id); ?>">
                        <?= escape($menu->title); ?>
                    </a>
                    <? else : ?>
                        <?= escape($menu->title); ?>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>
