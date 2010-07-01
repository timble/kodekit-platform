<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<style src="media://com_profiles/css/default.css" />
<script src="media://lib_koowa/js/koowa.js" />
					
<form action="<?= @route()?>" method="get">
	<input type="hidden" name="option" value="com_profiles" />
	<input type="hidden" name="view" value="people" />
	<input type="hidden" name="layout" value="grid" />
	<div class="people_filters">
		<h3><?=@text('People');?></h3>
			
		<?=@text('Search'); ?>: <input type="text" name="search" maxlength="40" value="<?=$state->search?>" /> 
		<?=@helper('admin::com.profiles.helper.select.departments', array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
		<?=@helper('admin::com.profiles.helper.select.offices', array('attribs' => array('onchange' => 'this.form.submit();'))) ?>
		<input type="submit" value="<?=@text('Go')?>" />
	</div>
</form>

<?= @template('admin::com.profiles.view.people.filter_name'); ?>

<form action="<?= @route()?>" method="post" name="adminForm">
	<p>
		<?=@text('View as');?> 
		<a href="<?=@route('view=people&layout=default') ?>" /> 
			<?=@text('List');?>
		</a> 
		<strong><?=@text('Grid');?></strong>
	</p>
	
	<?= @template('grid_items'); ?>

	<?= @helper('paginator.pagination', array('total' => $total)) ?>
</form>
