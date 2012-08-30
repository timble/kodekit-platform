<?
/**
 * @version     $Id: default.php 4878 2012-08-24 16:20:04Z tomjanssens
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
	<?= @template('default_scopebar'); ?>
	<table>
		<thead>
			<tr>
				<th width="10">
				    <?= @helper('grid.checkall'); ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'title' , 'title' => 'Name')) ?>
				</th>
				<th nowrap="nowrap" width="7%">
					<?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Published')) ?>
				</th>
				<th width="80" nowrap="nowrap">
					<?= @helper('grid.sort', array('column' => 'ordering' , 'title' => 'Order')) ?>
				</th>
				<th nowrap="nowrap" width="5%">
					<?= @helper('grid.sort', array('column' => 'pages' , 'title' => 'Pages')) ?>
				</th>
				<th nowrap="nowrap" class="title">
					<?= @helper('grid.sort', array('column' => 'type' , 'title' => 'Type')) ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<? if ($modules) : ?>
			<tr>
				<td colspan="20">
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
			<? endif ?>
		</tfoot>
		<tbody>
		<? foreach ($modules as $module) : ?>
			<tr>
				<td width="20" align="center">
					<?= @helper('grid.checkbox',array('row' => $module)) ?>
				</td>
				<td>
					<a href="<?= @route('view=module&id='.$module->id) ?>">
					    <?= @escape($module->title) ?>
					</a>
					<? if($module->access == '1') : ?>
					    <span class="label label-important"><?= @text('Registered') ?></span>
					<? elseif($module->access == '2') : ?>
					    <span class="label"><?= @text('Special') ?></span>
					<? endif; ?>
				</td>
				<td align="center" width="15px">
					<?= @helper('grid.enable', array('row' => $module)) ?>
				</td>
				<td class="order">
					<?= @helper('grid.order', array('row'=> $module))?>
				</td>
				<td align="center">
					<?= @text(
						is_array($module->pages) ? 'Varies' : $module->pages
					) ?>
				</td>
				<td>
					<?= @text(ucfirst($module->identifier->package)).' &raquo; '. @text(ucfirst($module->identifier->path[1])); ?>
				</td>
			</tr>
		<? endforeach ?>
		</tbody>
	</table>
</form>