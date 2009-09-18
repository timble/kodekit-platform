<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>
<? @style(@$mediaurl.'/com_beer/css/default.css'); ?>


<form action="<?= @route()?>" method="get" name="adminForm">
	<div class="people_filters">
	<h3><?=@text('People');?></h3>

	<?=@text('Search'); ?>: <input type="text" name="search" maxlength="40"
	value="<?=@$state->search?>" /> <?=@helper('admin::com.beer.helper.select.departments', @$state->beer_department_id) ?>
	<?=@helper('admin::com.beer.helper.select.offices', @$state->beer_office_id) ?>
	<input type="submit" value="<?=@text('Go')?>" /></div>
</form>
<br>
<p>
	<?=@text('View as');?> 
	<a href="<?=@route('view=people&layout=default') ?>" /> 
	<?=@text('List');?>
	</a> 
	<strong> <?=@text('Grid');?> </strong>
</p>

<?= $this->loadTemplate('items'); ?>


<?= @helper('paginator.limit', @$state->limit) ?>
<?= @helper('paginator.pages', @$total, @$state->offset, @$state->limit) ?>
