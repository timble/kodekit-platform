<?php 
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<? if( $state->section == 'com_articles') : ?>
<module title="" position="sidebar">
    <?= @template('default_sidebar'); ?>
</module>
<? endif; ?>

<form action="" method="get" class="-koowa-grid">
    <input type="hidden" name="section" value="<?= $state->section;?>" />
    <input type="hidden" name="type" value="<?= $state->type;?>" />

    <?= @template('default_scopebar'); ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                    <?= @helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= @helper('grid.sort',  array('column' => 'title')   ); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort',  array('column' => 'published')   ); ?>
                </th>
                <th width="8%" nowrap="nowrap">
                    <?= @text('Ordering') ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort',  array('title' => 'Access', 'column' => 'access')   ); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort',  array( 'title' => 'Num Items', 'column' => 'count') ); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="13">
                    <?= @helper('paginator.pagination', array('total' => $total)); ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <? foreach( $categories as $category) :  ?>
                <tr>
                    <td align="center">
                        <?= @helper( 'grid.checkbox' , array('row' => $category)); ?>
                    </td>
                    <td>
                        <a href="<?= @route( 'view=category&id='.$category->id ); ?>">
                            <?= @escape($category->title); ?>
                         </a>
                    </td>
                    <td align="center">
                        <?= @helper( 'grid.enable' , array('row' => $category)); ?>
                    </td>
                    <td class="order">
                        <?= @helper( 'grid.order' , array('row' => $category, 'total' => $category->order_total )); ?>
                    </td>
                    <td align="center">
                        <?= @helper( 'grid.access' , array('row' => $category)); ?>
                    </td>
                    <td align="center">
                        <?= $category->count; ?>
                    </td>
            	</tr>
            <? endforeach; ?>
       </tbody>
    </table>
</form>
