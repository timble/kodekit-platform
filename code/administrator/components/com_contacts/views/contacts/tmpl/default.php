<?php
/**
 * @version     $Id: form.php 2004 2011-06-26 16:32:54Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('default_sidebar') ?>

<form action="<?= @route()?>" method="get"  class="-koowa-grid">
 <?= @template('default_filter'); ?>
<table class="adminlist">
	<thead>
		<tr>
			<th width="10"></th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'name')); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'published')); ?>
			 </th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'ordering')); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'access')); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'user', 'title' => 'Linked to User')); ?>
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
				<?= @helper('listbox.enabled', array('name' => 'enabled')); ?>
			</td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
	</thead>

	<tfoot>
           <tr>
                <td colspan="20">
					 <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
			</tr>
	</tfoot>
		
	<tbody>
	<? foreach ($contacts as $contact) : ?>
		<tr>
			<td width="20" align="center">
				<?= @helper('grid.checkbox', array('row' => $contact))?>
			</td>				
			<td align="left">
				<a href="<?= @route('view=contact&id='.$contact->id); ?>">
	   				<?= @escape($contact->name); ?>
	   			</a>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $contact)); ?>
            </td>
			<td align="center">
				<?= @helper('grid.order', array('row' => $contact)); ?>
			</td>
			<td align="center">
				<?= @helper('grid.access', array('row' => $contact)) ?>
			</td>
			<td align="left">
				<?  if($contact->user_id) : ?>
				    <a href="<?= @route('option=com_users&view=user&id='.$contact->user_id) ?>">
				       <?= $contact->username; ?>
					</a>
				<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>	
</table>
</form>