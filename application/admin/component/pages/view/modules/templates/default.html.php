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
<? /* The application state is necessary in the url to avoid page redirects */ ?>
<?= helper('behavior.sortable', array('url' => '?format=json&application='.$state->application)) ?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<ktml:module position="sidebar">
	<?= import('default_sidebar.html'); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
	<?= import('default_scopebar.html'); ?>
	<table>
		<thead>
			<tr>
                <? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?><th class="handle"></th><? endif ?>
				<th width="1">
				    <?= helper('grid.checkall'); ?>
				</th>
                <th width="1"></th>
                <th>
					<?= helper('grid.sort', array('column' => 'title' , 'title' => 'Name')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort', array('column' => 'pages' , 'title' => 'Pages')) ?>
				</th>
				<th width="1">
					<?= helper('grid.sort', array('column' => 'type' , 'title' => 'Type')) ?>
				</th>
			</tr>
		</thead>
		<tfoot>
			<? if ($modules) : ?>
			<tr>
				<td colspan="20">
					<?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
			<? endif ?>
		</tfoot>
		<tbody<? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?> class="sortable"<? endif ?>>
		<? foreach ($modules as $module) : ?>
			<tr>
                <? if($state->position && $state->sort == 'ordering' && $state->direction == 'asc') : ?>
                <td class="handle">
                    <span class="text-small data-order"><?= $module->ordering ?></span>
                </td>
                <? endif ?>
				<td align="center">
					<?= helper('grid.checkbox',array('row' => $module)) ?>
				</td>
                <td align="center">
                    <?= helper('grid.enable', array('row' => $module, 'field' => 'published')) ?>
                </td>
				<td>
					<a href="<?= route('view=module&id='.$module->id) ?>">
					    <?= escape($module->title) ?>
					</a>
					<? if($module->access) : ?>
					    <span class="label label-important"><?= translate('Registered') ?></span>
					<? endif; ?>
				</td>
				<td align="center">
					<?= translate(
						is_array($module->pages) ? 'Varies' : $module->pages
					) ?>
				</td>
				<td>
					<?= translate(ucfirst($module->identifier->package)).' &raquo; '. translate(ucfirst($module->identifier->path[1])); ?>
				</td>
			</tr>
		<? endforeach ?>
		</tbody>
	</table>
</form>