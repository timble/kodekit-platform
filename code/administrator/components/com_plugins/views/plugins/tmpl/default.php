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

<?= @helper('behavior.tooltip') ?>

<div id="sidebar">
    <h3><?= @text( 'Types' ); ?></h3>
    <ul>
        <li <? if(!$state->folder) echo 'class="active"' ?>>
            <a href="<?= @route('&folder=') ?>">
                <?= @text('All types') ?>
            </a>
        </li>
        <? foreach($folders as $folder) : ?>
        <li <? if($state->folder == $folder->folder) echo 'class="active"' ?>>
            <a href="<?= @route('folder='.$folder->folder) ?>">
                <?= $folder->folder ?>
            </a>
        </li>
        <? endforeach ?>
    </ul>
</div>
<div class="-koowa-box-flex">
    <form action="<?= @route() ?>" method="get" class="-koowa-grid">
        <table class="adminlist">
            <thead>
            	<tr>
            		<th width="20"></th>
            		<th class="title">
            		    <?= @helper('grid.sort', array('column' => 'name' , 'title' => 'Name')) ?>
            		</th>
            		<th nowrap="nowrap" width="5%">
            			<?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Enabled')) ?>
            		</th>
            		<th width="80" nowrap="nowrap">
            			<?= @helper('grid.sort', array('column' => 'ordering' , 'title' => 'Order')) ?>
            		</th>
            		<th nowrap="nowrap" width="10%">
            			<?= @helper('grid.sort', array('column' => 'groupname' , 'title' => 'Access')) ?>
            		</th>
            	</tr>
            	<tr>
            		<td align="center">
            			<?= @helper( 'grid.checkall'); ?>
            		</td>
            		<td>
            			<?= @helper( 'grid.search'); ?>
            		</td>
            		<td align="center">
            			<?= @helper('listbox.enabled') ?>
            		</td>
            		<td colspan="2"></td>
            	</tr>
            </thead>
            <tfoot>
            	<? if ($plugins) : ?>
            	<tr>
            		<td colspan="5">
            			<?= @helper('paginator.pagination', array('total' => $total)) ?>
            		</td>
            	</tr>
            	<? endif ?>
            </tfoot>
            <tbody>
            <? foreach ($plugins as $plugin) : ?>
            	<tr>
            		<td width="20" align="center">
            			<?= @helper('grid.checkbox',array('row' => $plugin)) ?>
            		</td>
            		<td class="title">
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
            </tbody>
        </table>
    </form>
</div>