<?php
/**
 * @version     $Id: default.php 1990 2011-06-26 16:26:47Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Cache
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>
 
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('default_sidebar'); ?>

<form action="<?= @route() ?>" method="get" class="-koowa-grid">	
<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
			<th width="10">	
			</th>
			<th class="title" nowrap="nowrap">
				<?= @helper('grid.sort',  array('column' => 'name', 'title' => 'Key')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'size')); ?>
			</th>
			<th width="5%" align="center">
				<?= @helper('grid.sort',  array('column' => 'hits')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'created_on','title' => 'Created')); ?>
			</th>
			<th width="10%" align="center">
				<?= @helper('grid.sort',  array('column' => 'accessed_on','title' => 'Accessed')); ?>
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
	<? foreach($items as $item) : ?>
		<tr>
			<td align="center">
				<?= @helper( 'grid.checkbox' , array('row' => $item)); ?>
			</td>
			<td>
				<span class="bold">
					<?= $item->hash; ?>
				</span>
			</td>
			<td align="center">
				<?= number_format($item->size / 1024, 2) ?>
			</td>
			<td align="center">
				<?= $item->hits; ?>
			</td>
			<td align="center">
				<?= @helper('date.humanize', array('date' => $item->created_on)); ?>
			</td>
			<td align="center">
				<?= @helper('date.humanize', array('date' => $item->accessed_on)); ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>
</table>