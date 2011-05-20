<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip'); ?>
 
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route() ?>" method="get" name="adminForm" class="-koowa-grid">	
	<input type="hidden" name="scope" value="<?= $state->scope;?>" />
	<table class="adminlist">
		<thead>
			<tr>
				<th width="10">	
				</th>
				<th class="title">
					<?= @helper('grid.sort',  array('column' => 'title')   ); ?>
				</th>
				<th width="5%">
					<?= @helper('grid.sort',  array('column' => 'published')   ); ?>
				</th>
				<th width="8%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Order', 'column' => 'ordering')   ); ?>
				</th>
				<th width="10%">
					<?= @helper('grid.sort',  array('title' => 'Access', 'column' => 'groupname')   ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Categories', 'column' => 'categorycount') ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Active', 'column' => 'activecount') ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Trash', 'column' => 'trashcount') ); ?>
				</th>
			</tr>
			<tr>
				<td align="center">
					<?= @helper( 'grid.checkall'); ?>
				</td>
				<td>
					<?= @template('admin::com.default.view.grid.search_form') ?>
				</td>
				<td align="center"> 
					<?= @helper('listbox.published', array('name' => 'published', 'attribs' => array('onchange' => 'this.form.submit();'))); ?>
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
				<td colspan="13">
					<?= @helper('paginator.pagination', array('total' => $total)); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<? foreach ( $sections as $section ) :  ?>
			<tr>
				<td align="center">
					<?= @helper( 'grid.checkbox' , array('row' => $section)); ?>
				</td>
				<td>
					<span class="editlinktip hasTip" title="<?= @text( 'Description' ).'::'. @escape($section->description); ?>">
					<a href="<?= @route( 'view=section&id='.$section->id ); ?>">
                        <?= @escape($section->title); ?>
                    </a>
					</span>
				</td>
				<td align="center">
					<?= @helper( 'grid.publish' , array('row' => $section)); ?>
				</td>
				<td class="order">
					<?= @helper( 'grid.order' , array('row' => $section, 'total' => $total)); ?>
				</td>
				<td align="center">
					<?= @helper('grid.access', array( 'row' => $section)) ;?>
				</td>
				<td align="center">
					<?= $section->categorycount; ?>
				</td>
				<td align="center">
					<?= $section->activecount; ?>
				</td>
				<td align="center">
					<?= $section->trashcount; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>		
		</table>
		</form>		
