<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<div class="joomla">
	<form action="<?= @route()?>" method="get" name="adminForm">
	<div class="people_filters">
		<h3><?=@text('People');?></h3>
		<p></p>
		<?=@text('Search'); ?>:
		<input type="text" name="search" maxlength="40" value="<?=@$state->search?>" />
		<?=@helper('admin::com.beer.helper.select.departments', @$state->beer_department_id) ?>
		<?=@helper('admin::com.beer.helper.select.offices', @$state->beer_office_id) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
	</form>
	<form action="<?= @route()?>" method="post" name="adminForm">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tfoot>
			<tr>
				<td align="center" colspan="6" class="sectiontablefooter">
					<?= @helper('paginator.limit', @$state->limit) ?>
					<?= @helper('paginator.pages', @$total, @$state->offset, @$state->limit) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td class="sectiontableheader" width="5" align="center">
					<?= @text('NUM'); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Name', 'name', @$state->direction, @$state->order); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Position', 'position', @$state->direction, @$state->order); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Office', 'office', @$state->direction, @$state->order); ?>
				</td>
				<td class="sectiontableheader" align="left">
					<?= @helper('grid.sort', 'Department', 'department', @$state->direction, @$state->order); ?>
				</td>
			</tr>
			
			<?= $this->loadTemplate('items'); ?>
		</tbody>
	</table>
	</form>
</div>