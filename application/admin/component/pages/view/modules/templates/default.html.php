<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<? /* The application state is necessary in the url to avoid page redirects */ ?>
<?= helper('com:application.behavior.sortable', array(
    'url' => '?format=json&application='.parameters()->application
)) ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<ktml:block prepend="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:block>

<form action="" method="get" class="-koowa-grid">
	<?= import('default_scopebar.html'); ?>
	<table>
		<thead>
			<tr>
                <? if(parameters()->position && parameters()->sort == 'ordering' && parameters()->direction == 'asc') : ?><th class="handle"></th><? endif ?>
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
					<?= helper('com:application.paginator.pagination') ?>
				</td>
			</tr>
			<? endif ?>
		</tfoot>
		<tbody<? if(parameters()->position && parameters()->sort == 'ordering' && parameters()->direction == 'asc') : ?> class="sortable"<? endif ?>>
		<? foreach ($modules as $module) : ?>
			<tr>
                <? if(parameters()->position && parameters()->sort == 'ordering' && parameters()->direction == 'asc') : ?>
                <td class="handle">
                    <span class="text--small data-order"><?= $module->ordering ?></span>
                </td>
                <? endif ?>
				<td align="center">
					<?= helper('grid.checkbox',array('entity' => $module)) ?>
				</td>
                <td align="center">
                    <?= helper('grid.enable', array('entity' => $module, 'field' => 'published')) ?>
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