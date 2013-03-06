<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://koowa/js/koowa.js" />
<style src="media://koowa/css/koowa.css" />
-->
<?= @helper('behavior.sortable') ?>

<?= @template('com://admin/default.view.grid.toolbar.html'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar.html'); ?>
</ktml:module>

<ktml:module position="inspector">
    <?= @template('com://admin/activities.view.activities.simple.html', array('package' => 'contacts', 'name' => 'contact')); ?>
</ktml:module>

<form action="" method="get"  class="-koowa-grid">
<?= @template('default_scopebar.html'); ?>
<table>
	<thead>
		<tr>
            <? if($sortable) : ?>
            <th class="handle"></th>
            <? endif ?>
			<th width="10">
			    <?= @helper('grid.checkall'); ?>
			</th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'name')); ?>
			</th>
			<th width="5%" nowrap="nowrap">
			    <?= @helper('grid.sort', array('column' => 'published')); ?>
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
		
	<tbody<?= $sortable ? ' class="sortable"' : '' ?>>
	<? foreach ($contacts as $contact) : ?>
		<tr>
            <? if($sortable) : ?>
            <td class="handle">
                <span class="text-small data-order"><?= $contact->ordering ?></span>
            </td>
            <? endif ?>
			<td width="20" align="center">
				<?= @helper('grid.checkbox', array('row' => $contact))?>
			</td>				
			<td align="left">
				<a href="<?= @route('view=contact&id='.$contact->id); ?>">
	   				<?= @escape($contact->name); ?>
	   			</a>
	   			<? if($contact->access) : ?>
	   			    <span class="label label-important"><?= @text('Registered') ?></span>
	   			<? endif; ?>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $contact, 'field' => 'published')) ?>
            </td>
		</tr>
	<? endforeach; ?>
	</tbody>	
</table>
</form>