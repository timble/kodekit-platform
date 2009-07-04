<? defined('_JEXEC') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<div class="joomla ">
	<form action="<?= @route()?>" method="post" name="adminForm">
	<input type="hidden" name="action" value="browse" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?= @$filter['order']; ?>" />
	<input type="hidden" name="filter_direction" value="<?= @$filter['direction']; ?>" />
	<div class="people_filters">
		<h3><?=@text('People');?></h3>
		<p></p>
		<?=@text('Search'); ?>:
		<input type="text" name="search" maxlength="40" value="<?=@$filter['search']?>" />
		<?=@helper('admin::com.beer.helper.select.departments', @$filter['beer_department_id']) ?>
		<?=@helper('admin::com.beer.helper.select.offices', @$filter['beer_office_id']) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tfoot>
			<tr>
				<td align="center" colspan="6" class="sectiontablefooter">
					<?= @$pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="sectiontableheader" width="5" align="center">
					<?= @text('NUM'); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Name', 'name', @$filter['direction'], @$filter['order']); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Position', 'Position', @$filter['direction'], @$filter['order']); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Office', 'Office', @$filter['direction'], @$filter['order']); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Department', 'Department', @$filter['direction'], @$filter['order']); ?>
				</td>
			</tr>
			<?php echo $this->loadTemplate('items'); ?>
		</tbody>
	</table>
	</form>
</div>