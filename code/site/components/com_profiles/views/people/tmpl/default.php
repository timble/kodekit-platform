<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_default/css/site.css" />
<style src="media://com_profiles/css/default.css" />
<script src="media://lib_koowa/js/koowa.js" />

<h3><?=@text('People');?></h3>
	
<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="people" />
	
	<div class="people_filters">
		<?=@text('Search'); ?>:
		<input type="text" name="search" maxlength="40" value="<?=$state->search?>" />
		<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>
		<?=@helper('admin::com.profiles.helper.listbox.departments') ?>
		<?=@helper('admin::com.profiles.helper.listbox.offices') ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
</form>

<?= @template('admin::com.default.view.list.search_letters'); ?>

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
					<?= @helper('paginator.pagination', array('total' => $total)) ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<tr>
				<td width="5" align="center" class="sectiontableheader">
					<?= @text('NUM'); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', array('column' => 'name')); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', array('column' => 'position')); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', array('column' => 'office')); ?>
				</td>
				<td align="left" class="sectiontableheader">
					<?= @helper('grid.sort', array('column' => 'department')); ?>
				</td>
			</tr>
				
			<?= @template('default_items'); ?>
			
		</tbody>
	</table>
</form>