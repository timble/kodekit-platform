<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
 
defined('KOOWA') or die('Restricted access'); ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<module title="" position="sidebar">
    <?= @template('default_sidebar'); ?>
</module>

<form action="" method="get" class="-koowa-grid">
    <?= @template('default_filter'); ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                    <?= @helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'title')); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort', array('column' => 'showbanner', 'title' => 'published')); ?>
                </th>
                <th width="8%">
                    <?= @helper('grid.sort', array('column' => 'ordering')); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort', array('column' => 'sticky')); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort', array('column' => 'hits')); ?>
                </th>
                <th width="5%">
                    <?= @text('Tags'); ?>
                </th>
            </tr>
        </thead>
        
        <tfoot>
            <tr>
                <td colspan="7">
                <?= @helper('paginator.pagination', array('total' => $total)); ?>
                </td>
            </tr>
        </tfoot>
        
        <tbody>
            <? foreach ($banners as $banner) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox', array('row' => $banner))?>
                </td>
                <td align="left">
                	<a href="<?= @route('view=banner&id='.$banner->id); ?>">
                        <?=$banner->title; ?>
                    </a>
                </td>
                <td align="center">
                    <?= @helper('grid.enable', array('row' => $banner)) ?>
                </td>
                <td align="center">
                    <?= @helper( 'grid.order' , array('row' => $banner, 'total' => $banner->order_total)); ?>
                </td>
                <td align="center">
                    <?= $banner->sticky ? @text('Yes') : @text('No'); ?>
                </td>
                <td align="center">
                    <?= $banner->hits;?>
                </td>
                <td align="center">
                    <?=$banner->tags?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</form>