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

<?= @helper('behavior.tooltip');?>
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?=@route()?>" method="get" class="-koowa-grid">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%"></th>
                <th width="48%">
                    <?= @helper('grid.sort', array('column' => 'name')); ?>
                </th>
                <th width="10%">
                    <?= @helper('grid.sort', array('column' => 'category')); ?>
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
                    <?= @helper('grid.sort', array('column' => 'impmade', 'title' => 'Impressions')); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort', array('column' => 'clicks')); ?>
                </th>
                <th width="5%">
                    <?= @text('Tags'); ?>
                </th>
            </tr>
            <tr>
                <td>
                    <?= @helper( 'grid.checkall'); ?>
                </td>
                <td>
                    <?= @text('Filter:'); ?> <?= @template('admin::com.default.view.grid.search_form'); ?>
                </td>
               
                <td align="center"> 
                    <?= @helper('listbox.categories', array(
                        'name' => 'category', 'attribs' => array('onchange' => 'this.form.submit();'))); ?>
                </td>                
                <td align="center"> 
                    <?= @helper('listbox.published', array( 'name' => 'enabled', 
                        'attribs' => array('onchange' => 'this.form.submit();'))); ?>
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
                <td colspan="9">
                <?= @helper('paginator.pagination', array('total' => $total)); ?>
                </td>
            </tr>
        </tfoot>
        
        <tbody>
        <? if (count($banners)) : ?>
            <? foreach ($banners as $banner) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox', array('row' => $banner))?>
                </td>
                <td align="left">
                    <span class="editlinktip hasTip" title="<?= @text('Edit banner')?>::<?= @escape($banner->name); ?>">
                        <a href="<?= @route('view=banner&id='.$banner->id); ?>">
                            <?=$banner->name?>
                        </a>
                    </span>
                </td>
                <td align="center">
                    <?=$banner->category?>
                </td>
                <td align="center">
                    <?= @helper('grid.enable', array('row' => $banner)) ?>
                </td>
                <td align="center">
                    <?= @helper( 'grid.order' , array('row' => $banner, 'total' => $banner->total)); ?>
                </td>
                <td align="center">
                    <?=$banner->sticky?>
                </td>
                <td align="center">
                    <?=$banner->hits?>
                </td>
                <td align="center">
                    <?= $banner->clicks;?> -
				    <?= sprintf( '%.2f%%', 100 * ($banner->clicks/$banner->hits));?>
                </td>
                <td align="center">
                    <?=$banner->tags?>
                </td>
            </tr>
            <? endforeach; ?>
        <? else : ?>
            <tr>
                <td colspan="9" align="center">
                    <?= @text('No Items Found'); ?>
                </td>
            </tr>
        <? endif; ?>
        </tbody>
    </table>
</form>
