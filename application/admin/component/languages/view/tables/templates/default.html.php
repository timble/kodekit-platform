<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="get" class="-koowa-grid">
    <?= import('default_scopebar.html') ?>
	<table>
		<thead>
			<tr>
			    <th width="1">
				    <?= helper('grid.checkall') ?>
				</th>
				<th>
					<?= translate('Name') ?>
				</th>
				<th width="1">
					<?= translate('Enabled') ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="3">
					 <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<? foreach($tables as $table) : ?>
			<tr>
			    <td align="center">
					<?= helper('grid.checkbox', array('row' => $table)) ?>
				</td>
				<td>
					<?= escape($table->name) ?>
				</td>
				<td align="center">
					<?= helper('grid.enable', array('row' => $table)) ?>
				</td>
			</tr>
			<? endforeach ?>
		</tbody>
	</table>
</form>