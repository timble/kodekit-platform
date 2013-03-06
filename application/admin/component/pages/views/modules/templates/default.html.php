<?
/**
 * @package     Nooku_Server
 * @subpackage  Pages
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

<form action="" method="get" class="-koowa-grid">
	<?= @template('default_scopebar.html'); ?>
	<table>
		<thead>
			<tr>
                <? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?><th class="handle"></th><? endif ?>
				<th width="1">
				    <?= @helper('grid.checkall'); ?>
				</th>
				<th>
					<?= @helper('grid.sort', array('column' => 'title' , 'title' => 'Name')) ?>
				</th>
				<th width="1">
					<?= @helper('grid.sort', array('column' => 'published' , 'title' => 'Published')) ?>
				</th>
				<th width="1">
					<?= @helper('grid.sort', array('column' => 'ordering' , 'title' => 'Order')) ?>
				</th>
				<th width="1">
					<?= @helper('grid.sort', array('column' => 'pages' , 'title' => 'Pages')) ?>
				</th>
				<th width="1">
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
		<tbody<? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?> class="sortable"<? endif ?>>
		<? foreach ($modules as $module) : ?>
			<tr>
                <? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?><td class="handle"></td><? endif ?>
				<td align="center">
					<?= @helper('grid.checkbox',array('row' => $module)) ?>
				</td>
				<td>
					<a href="<?= @route('view=module&id='.$module->id) ?>">
					    <?= @escape($module->title) ?>
					</a>
					<? if($module->access) : ?>
					    <span class="label label-important"><?= @text('Registered') ?></span>
					<? endif; ?>
				</td>
				<td align="center">
					<?= @helper('grid.enable', array('row' => $module, 'field' => 'published')) ?>
				</td>
				<td align="center">
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