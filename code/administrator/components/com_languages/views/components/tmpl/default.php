<?
/**
 * @version     $Id: form.php 3029 2011-10-09 13:07:11Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="" method="get" class="-koowa-grid">
    <?= @template('default_scopebar') ?>
	<table>
		<thead>
			<tr>
			    <th width="10">
				    <?= @helper('grid.checkall') ?>
				</th>
				<th>
					<?= @text('Name') ?>
				</th>
				<th>
					<?= @text('Enabled') ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="2">
					 <?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<? foreach($components as $component) : ?>
			<tr>
			    <td align="center">
					<?= @helper('grid.checkbox', array('row' => $component)) ?>
				</td>
				<td>
					<?= @escape($component->title) ?>
				</td>
				<td align="center">
					<?= @helper('grid.enable', array('row' => $component)) ?>   
				</td>
			</tr>
			<? endforeach ?>
		</tbody>
	</table>
</form>