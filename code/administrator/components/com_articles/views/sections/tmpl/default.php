<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>
 
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<form action="" method="get" class="-koowa-grid">	
	<?= @template('default_filter'); ?>
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
					<?= @helper('grid.sort',  array( 'title' => 'Order', 'column' => 'ordering')   ); ?>
				</th>
				<th width="10%">
					<?= @helper('grid.sort',  array('title' => 'Access', 'column' => 'access')   ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Categories', 'column' => 'categorycount') ); ?>
				</th>
				<th width="5%" nowrap="nowrap">
					<?= @helper('grid.sort',  array( 'title' => 'Num Active', 'column' => 'activecount') ); ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
					<?= @helper('paginator.pagination', array('total' => $total)); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<? foreach ( $sections as $category ) :  ?>
			<tr>
				<td align="center">
					<?= @helper( 'grid.checkbox' , array('row' => $category)); ?>
				</td>
				<td>
					<a href="<?= @route( 'view=section&id='.$category->id ); ?>">
                        <?= @escape($category->title); ?>
                    </a>
				</td>
				<td align="center">
					<?= @helper('grid.enable', array('row' => $category)) ?>
				</td>
				<td class="order">
					<?= @helper( 'grid.order' , array('row' => $category, 'total' => $total)); ?>
				</td>
				<td align="center">
					<?= @helper('grid.access', array( 'row' => $category)) ;?>
				</td>
				<td align="center">
					<?= $category->categorycount; ?>
				</td>
				<td align="center">
					<?= $category->activecount; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>		
	</table>
</form>		