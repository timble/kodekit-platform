<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>
<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<h3><?=@text('People');?></h3>
	
<?= @text('Sort by first letter of firstname'); ?>:
<?= @template('filter_firstname'); ?>
				
<?= @text('Sort by first letter of lastname'); ?>:
<?= @template('filter_lastname'); ?>

<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_beer" />
	<input type="hidden" name="view" value="people" />
	
	<div class="people_filters">
		<?=@text('Search'); ?>:
		<input type="text" name="search" maxlength="40" value="<?=@$state->search?>" />
		<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>
		<?=@helper('admin::com.beer.helper.select.departments', @$state->beer_department_id, 'beer_department_id', $attribs, '', true) ?>
		<?=@helper('admin::com.beer.helper.select.offices', @$state->beer_office_id, 'beer_office_id', $attribs, '', true) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
</form>

<div>
	<?=@text('View as');?> 
	<strong><?=@text('List');?></strong>
	<a href="<?=@route('view=people&layout=grid') ?>" /><?=@text('Grid');?></a>
</div>
		
<form action="<?= @route()?>" method="post" class="adminForm">
	<table width="100%">
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
				<td width="5" align="center" class="sectiontableheader">
					<?= @text('NUM'); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', 'Name', 'name', @$state->direction, @$state->order); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', 'Position', 'position', @$state->direction, @$state->order); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', 'Office', 'office', @$state->direction, @$state->order); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', 'Department', 'department', @$state->direction, @$state->order); ?>
				</td>
			</tr>
				
			<?= @template('default_items'); ?>
			
		</tbody>
	</table>
</form>