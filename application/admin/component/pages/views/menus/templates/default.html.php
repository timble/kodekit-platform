<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://koowa/js/koowa.js" />
<style src="media://koowa/css/koowa.css" />
-->

<?= @template('com://admin/base.view.form.toolbar.html'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar.html') ?>
</ktml:module>

<form id="menus-form" action="<?= @route() ?>" method="get" class="-koowa-grid">
    <table>
        <thead>
            <tr>
                <th width="1">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($menus); ?>);" />
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'title' , 'title' => 'Title')); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="4">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <? foreach($menus as $menu) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox',array('row' => $menu)); ?>
                </td>
                <td>
                    <? if(!$state->trash) : ?>
                    <a href="<?= @route('view=menu&id='.$menu->id); ?>">
                        <?= @escape($menu->title); ?>
                    </a>
                    <? else : ?>
                        <?= @escape($menu->title); ?>
                    <? endif ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>
