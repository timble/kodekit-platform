<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
 
defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.modal') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('default_sidebar') ?>

<form action="<?=@route()?>" method="get" class="-koowa-grid">
    <?= @template('default_filter'); ?>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="2%"></th>
                <th width="48%">
                    <?= @helper('grid.sort', array('column' => 'name')); ?>
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
                    <?= @helper('grid.sort', array('column' => 'clicks')); ?>
                </th>
                <th width="5%">
                    <?= @text('Tags'); ?>
                </th>
            </tr>
            <tr>
                <td align="center">
                    <?= @helper( 'grid.checkall'); ?>
                </td>
                <td>
                    <?= @helper( 'grid.search'); ?>
                </td>              
                <td></td>                
                <td></td>
                <td></td>
                <td></td>
                <td></td>
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
                        <?=$banner->name?>
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
                    <?= $banner->clicks;?>
                </td>
                <td align="center">
                    <?=$banner->tags?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</form>