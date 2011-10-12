<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @template('default_sidebar'); ?>

<form action="" method="get" class="-koowa-grid">
    <?= @template('default_filter'); ?>
    <table class="adminlist">
        <thead>
        	<tr>
        		<th width="20"></th>
        		<th>
        		    <?= @helper('grid.sort', array('column' => 'name' , 'title' => 'Name')) ?>
        		</th>
        		<th nowrap="nowrap" width="5%">
        			<?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Enabled')) ?>
        		</th>
        		<th width="80" nowrap="nowrap">
        			<?= @helper('grid.sort', array('column' => 'ordering' , 'title' => 'Order')) ?>
        		</th>
        		<th nowrap="nowrap" width="10%">
        			<?= @helper('grid.sort', array('column' => 'access' , 'title' => 'Access')) ?>
        		</th>
        	</tr>
        	<tr>
        		<td align="center">
        			<?= @helper( 'grid.checkall'); ?>
        		</td>
        		<td>
        			<?= @helper( 'grid.search'); ?>
        		</td>
        		<td colspan="3"></td>
        	</tr>
        </thead>
        <tfoot>
        	<tr>
        		<td colspan="5">
        			<?= @helper('paginator.pagination', array('total' => $total)) ?>
        		</td>
        	</tr>
        </tfoot>
        <tbody>
        <? foreach ($plugins as $plugin) : ?>
        	<tr>
        		<td align="center">
        			<?= @helper('grid.checkbox',array('row' => $plugin)) ?>
        		</td>
        		<td>
        		<? if(!$plugin->locked()) : ?>
        			<a href="<?= @route('view=plugin&id='.$plugin->id) ?>">
        				<?= @escape($plugin->title) ?>
        			</a>
        		<? else : ?>
        			<?= @escape($plugin->title) ?>
        		<? endif ?>
        		</td>
        		<td align="center" width="15px">
        			<?= @helper('grid.enable', array('row' => $plugin)) ?>
        		</td>
        		<td class="order">
        			<?= @helper('grid.order', array('row'=> $plugin))?>
        		</td>
        		<td align="center">
        			<?= @helper('grid.access', array('row' => $plugin)) ?>
        		</td>
        	</tr>
        <? endforeach ?>
        <? if (!$total) : ?>
        	<tr>
                <td colspan="8" align="center">
                     <?= @text('No Items Found'); ?>
                </td>
            </tr>
        <? endif ?>
        </tbody>
    </table>
</form>