<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<!--
<script src="media://koowa/js/koowa.js" />
<style src="media://koowa/css/koowa.css" />
-->
<?= @helper('behavior.sortable') ?>

<?= @template('com://admin/base.view.grid.toolbar.html'); ?>

<ktml:module position="sidebar">
	<?= @template('default_sidebar.html'); ?>
</ktml:module>

<ktml:module position="inspector">
    <?= @template('com://admin/activities.view.activities.simple.html', array('package' => 'weblinks', 'name' => 'weblink')); ?>
</ktml:module>

<form action="" method="get" class="-koowa-grid">
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
			<th>
				<?= @helper('grid.sort', array('column' => 'title')) ?>
			</th>
			<th width="1">
				<?= @helper('grid.sort', array('column' => 'published')) ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="4">
				<?= @helper('paginator.pagination', array('total' => $total)) ?>
			</td>
		</tr>
	</tfoot>
	<tbody<?= $sortable ? ' class="sortable"' : '' ?>>
		<? foreach ($weblinks as $weblink) : ?>
		<tr>
            <? if($sortable) : ?>
            <td class="handle">
                <span class="text-small data-order"><?= $weblink->ordering ?></span>
            </td>
            <? endif ?>
			<td align="center">
				<?= @helper('grid.checkbox', array('row' => $weblink))?>
			</td>
			<td>
				<a href="<?= @route( 'view=weblink&id='. $weblink->id ); ?>"><?= @escape($weblink->title); ?></a>
			</td>
			<td align="center">
				<?= @helper('grid.enable', array('row' => $weblink, 'field' => 'published')) ?>
			</td>
		</tr>
		<? endforeach; ?>
	</tbody>
	</table>
</form>