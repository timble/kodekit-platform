<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>

<? @script(@$mediaurl.'/plg_koowa/js/koowa.js'); ?>
					
<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_beer" />
	<input type="hidden" name="view" value="people" />
	<input type="hidden" name="layout" value="grid" />
	<div class="people_filters">
		<h3><?=@text('People');?></h3>
			
		<?=@text('Search'); ?>: <input type="text" name="search" maxlength="40" value="<?=@$state->search?>" /> 
		<? $attribs = array('class' => 'inputbox', 'size' => '1', 'onchange' => 'this.form.submit();');?>
		<?=@helper('admin::com.beer.helper.select.departments', @$state->beer_department_id, 'beer_department_id', $attribs, '', true) ?>
		<?=@helper('admin::com.beer.helper.select.offices', @$state->beer_office_id, 'beer_office_id', $attribs, '', true) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
</form>

<?= @template('filter_name'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<p>
		<?=@text('View as');?> 
		<a href="<?=@route('view=people&layout=default') ?>" /> 
			<?=@text('List');?>
		</a> 
		<strong><?=@text('Grid');?></strong>
	</p>
	
	<?= @template('grid_items'); ?>

	<?= @helper('paginator.pagination', @$total, @$state->offset, @$state->limit) ?>
</form>
