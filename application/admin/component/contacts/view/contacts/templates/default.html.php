<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->
<?= helper('behavior.sortable') ?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<ktml:module position="sidebar">
	<?= import('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get"  class="-koowa-grid">
<?= import('default_scopebar.html'); ?>
<table>
	<thead>
		<tr>
            <? if($sortable) : ?>
            <th class="handle"></th>
            <? endif ?>
			<th width="1">
			    <?= helper('grid.checkall'); ?>
			</th>
            <th width="1">

            </th>
			<th>
			    <?= helper('grid.sort', array('column' => 'name')); ?>
			</th>
		</tr>		
	</thead>

	<tfoot>
        <tr>
            <td colspan="20">
                 <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
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
				<?= helper('grid.checkbox', array('row' => $contact))?>
			</td>
            <td align="center">
                <?= helper('grid.enable', array('row' => $contact, 'field' => 'published')) ?>
            </td>
			<td>
				<a href="<?= route('view=contact&id='.$contact->id); ?>">
	   				<?= escape($contact->name); ?>
	   			</a>
	   			<? if($contact->access) : ?>
	   			    <span class="label label-important"><?= translate('Registered') ?></span>
	   			<? endif; ?>
			</td>
		</tr>
	<? endforeach; ?>
	</tbody>	
</table>
</form>