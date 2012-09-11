<?
/**
 * @version     $Id: default.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar') ?>
</ktml:module>

<form id="menus-form" action="<?= @route()?>" method="get" class="-koowa-grid">
    <table>
        <thead>
            <tr>
                <th width="5">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($menus); ?>);" />
                </th>
                <th width="100%">
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
