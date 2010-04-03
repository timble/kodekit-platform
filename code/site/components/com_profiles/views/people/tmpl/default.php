<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @style(@$mediaurl.'/com_profiles/css/default.css'); ?>
<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>

<h3><?=@text('People');?></h3>
	
<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="people" />
	
	<div class="people_filters">
		<?=@text('Search'); ?>:
		<input type="text" name="search" maxlength="40" value="<?=@$state->search?>" />
		<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>
		<?=@helper('admin::com.profiles.helper.select.departments', @$state->profiles_department_id, 'profiles_department_id', $attribs, '', true) ?>
		<?=@helper('admin::com.profiles.helper.select.offices', @$state->profiles_office_id, 'profiles_office_id', $attribs, '', true) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
</form>

<?= @template('admin::com.profiles.view.people.filter_name'); ?>

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
					<?= @helper('paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
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