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
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->
<?= @helper('behavior.sortable') ?>

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<ktml:module position="sidebar">
	<?= @template('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get"  class="-koowa-grid">
<?= @template('default_scopebar.html'); ?>
<table>
	<thead>
		<tr>
            <? if($sortable) : ?>
            <th class="handle"></th>
            <? endif ?>
			<th width="1">
			    <?= @helper('grid.checkall'); ?>
			</th>
            <th width="1">

            </th>
			<th>
			    <?= @helper('grid.sort', array('column' => 'name')); ?>
			</th>
		</tr>		
	</thead>

	<tfoot>
        <tr>
            <td colspan="20">
                 <?= @helper('com:application.paginator.pagination', array('total' => $total)) ?>
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
			<td align="center">
				<?= @helper('grid.checkbox', array('row' => $contact))?>
			</td>
            <td align="center">
                <?= @helper('grid.enable', array('row' => $contact, 'field' => 'published')) ?>
            </td>
			<td>
				<a href="<?= @route('view=contact&id='.$contact->id); ?>">
	   				<?= @escape($contact->name); ?>
	   			</a>
	   			<? if($contact->access) : ?>
	   			    <span class="label label-important"><?= @text('Registered') ?></span>
	   			<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>	
</table>
</form>