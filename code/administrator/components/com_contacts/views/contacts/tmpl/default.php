<?php
/**
 * @version     $Id: form.php 2004 2011-06-26 16:32:54Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<module title="" position="sidebar">
	<?= @template('default_sidebar'); ?>
</module>

<module title="" position="inspector">
    <?= @template('com://admin/activities.view.activities.simple', array('package' => 'contacts', 'name' => 'contact')); ?>
</module>

<form action="" method="get"  class="-koowa-grid">
<?= @template('default_scopebar'); ?>
<table>
	<thead>
		<tr>
			<th width="10">
			    <?= @helper('grid.checkall'); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'name')); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'published')); ?>
			 </th>
			 <th>
			     <?= @helper('grid.sort', array('column' => 'category_title', 'title' => 'Category')) ?>
			 </th>
			<? if($state->category) : ?>
			<th>
			    <?= @helper('grid.sort', array('column' => 'ordering')); ?>
			</th>
			<? endif ?>
			<th>
			    <?= @helper('grid.sort', array('column' => 'user', 'title' => 'Linked to User')); ?>
			 </th>
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
	   			<? if($contact->access == '1') : ?>
	   			    <span class="label label-important"><?= @text('Registered') ?></span>
	   			<? elseif($contact->access == '2') : ?>
	   			    <span class="label"><?= @text('Special') ?></span>
	   			<? endif; ?>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $contact)); ?>
            </td>
            <td>
                <?= $contact->category_title ?>
            </td>
			<? if($state->category) : ?>
			<td align="center">
				<?= @helper('grid.order', array('row' => $contact)); ?>
			</td>
			<? endif ?>
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