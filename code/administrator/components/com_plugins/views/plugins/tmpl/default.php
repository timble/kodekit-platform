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

<div class="col width-15 menus">
    <ul>
        <li <? if(!$state->folder) echo 'class="active"' ?>>
            <a href="<?= @route('&folder=') ?>">
                <?= @text('all') ?>
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
<div class="col width-85">
    <form action="<?= @route() ?>" method="get" name="adminForm">
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
            		<th nowrap="nowrap"  width="10%" class="title">
            		    <?= @helper('grid.sort', array('column' => 'folder' , 'title' => 'Type')) ?>
            		</th>
            	</tr>
            	<tr>
            		<td align="center">
            			<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count($plugins) ?>);" />
            		</td>
            		<td>
            			<?= @text( 'Filter' ) ?>:
            			<?= @template('admin::com.default.view.list.search_form') ?>
            		</td>
            		<td align="center">
            			<?= @helper('listbox.enabled') ?>
            		</td>
            		<td colspan="3"></td>
            	</tr>
            </thead>
            <tfoot>
            	<? if ($plugins) : ?>
            	<tr>
            		<td colspan="20">
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
            		<td nowrap="nowrap">
            			<?= $plugin->folder ?>
            		</td>
            	</tr>
            <? endforeach ?>
            </tbody>
        </table>
    </form>
</div>