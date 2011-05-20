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

/**
 * Banners HTML template - Clients
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip');?>
<script src="media://system/js/mootools.js" />  
<script src="media://lib_koowa/js/koowa.js" />

<form action="<?=@route()?>" method="get" name="adminForm">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="1%"><?= @text('Num'); ?></th>
                <th width="1%"></th>
                <th width="48%" style="text-align: left">
                    <?= @helper('grid.sort', array('column' => 'name')); ?>
                </th>
                <th width="40%" style="text-align: left">
                    <?= @helper('grid.sort', array('column' => 'contact')); ?>
                </th>
                <th width="10%" style="text-align: left">
                    <?= @helper('grid.sort', array('column' => 'banners')); ?>
                </th>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($clients); ?>);" />
                </td>
                <td>
                    <?= @text('Filter:'); ?> <?= @template('admin::com.default.view.list.search_form'); ?>
                </td>
                <td colspan="2"></td>
            </tr>
        </thead>
        
        <tfoot>
            <tr>
                <td colspan="5">
                <?= @helper('paginator.pagination', array('total' => $total)); ?>
                </td>
            </tr>
        </tfoot>
        
        <tbody>
        <? if (count($clients)) : ?>
            <? $i = 0; $m = 0; ?>
            <? foreach ($clients as $client) : ?>
            <tr class="row<?=$m?>">
                <td><?= $i + 1; ?></td>
                <td align="center">
                    <?= @helper('grid.checkbox', array('row' => $client))?>
                </td>
                <td align="left">
                    <span class="editlinktip hasTip" title="<?= @text('Edit client')?>::<?= @escape($client->name); ?>">
                        <a href="<?= @route('view=client&id='.$client->id); ?>">
                            <?=$client->name?>
                        </a>
                    </span>
                </td>
                <td align="left">
                    <?=$client->contact?>
                </td>
                <td align="left">
                    <?=$client->banners?>
                </td>
            </tr>
            <? $i = $i + 1; $m = (1 - $m);?>
            <? endforeach; ?>
        <? else : ?>
            <tr>
                <td colspan="5" align="center">
                    <?= @text('No Items Found'); ?>
                </td>
            </tr>
        <? endif; ?>
        </tbody>
    </table>
</form>