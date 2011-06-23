<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<style src="media://lib_koowa/css/koowa.css" />
<script src="media://lib_koowa/js/koowa.js" />

<?= @toolbar(); ?>

<?= @template('default_sidebar') ?>

<form action="<?= @route()?>" method="get" class="-koowa-grid">
	<?= @template('default_filter') ?>
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10"></th>
    			<th>
    			    <?= @helper('grid.sort', array('column' => 'name')) ?>
    			</th>
    			<th width="7%">
    			    <?= @helper('grid.sort', array('column' => 'published', 'title' => 'Published')) ?>
    			</th>
    			<th width="8%" nowrap="nowrap">
    			    <?= @helper('grid.sort', array('column' => 'ordering', 'title' => 'Order')) ?>
    			</th>
    			<th width="7%" nowrap="nowrap">
    			    <?= @helper('grid.sort', array('column' => 'numarticles', 'title' => 'Num Articles')) ?>
    			</th>
    			<th width="7%" nowrap="nowrap">
    			    <?= @helper('grid.sort', array('column' => 'cache_time', 'title' => 'Cache time')) ?>
    			</th>
             </tr>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkall') ?>
                </td>
                <td>
                    <?= @helper('grid.search') ?>
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
            <? foreach($newsfeeds as $newsfeed) : ?>
        		<tr>
                    <td align="center">
                        <?= @helper('grid.checkbox', array('row' => $newsfeed)) ?>
                    </td>
                    <td>
                    	<a href="<?= @route('view=newsfeed&id='.$newsfeed->id) ?>">
                    	    <?= @escape($newsfeed->name) ?>
                    	</a>
                    </td>
                    <td align="center">
                        <?= @helper('grid.enable', array('row' => $newsfeed)) ?>
                    </td>
                    <td class="order">
                        <?= @helper( 'grid.order' , array('row' => $newsfeed)) ?>
                    </td>
                    <td align="center">
                        <?= $newsfeed->numarticles ?>
                    </td>
                    <td align="center">
                        <?= $newsfeed->cache_time ?>
                    </td>
                </tr>
            <? endforeach ?>
        </tbody>
    </table>
</form>