<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.validator') ?>

<script inline>
window.addEvent('domready', function(){
	/* Reset the filter values to blank */
	document.id('activities-filter').addEvent('reset', function(e){
		e.target.getElements('input').each(function(el){
			if(['days_back','start_date', 'user'].contains(el.name)){
				el.value = '';
			}
		});
		e.target.submit();
	});
});
</script>


<h3><?=@text( 'Components' )?></h3>
<div class="scrollable">
	<nav>
		<a class="<?= empty($state->package) ? 'active' : ''; ?>" href="<?= @route('package=') ?>">
		<?= @text('All components')?>
		</a>
		<?php foreach ($packages as $package): ?>
		<a <?= $package->id == $state->package ? 'class="active"' : '' ?> href="<?=@route('package='.$package->id)?>"><?=ucfirst($package->package)?></a>
		<?php endforeach ?>
	</nav>
		
	<form action="" method="get" class="activities-filter">
		<fieldset>
		    <legend><?=@text( 'Filters' )?></legend>
		    <div>
				<label for="start_date"><?=@text( 'Start Date' )?></label>
				<div class="controls-calendar">
					<?= @helper('behavior.calendar',
							array(
								'date' => $state->start_date,
								'name' => 'start_date',
								'format' => '%Y-%m-%d'
							)); ?>
				</div>
	
				<label for="days_back"><?=@text( 'Days Back' )?></label>
				<div class="activities-days-back">
					<input type="text" size="3" name="days_back" value="<?=($state->days_back) ? $state->days_back : '' ?>" />
				</div>
	
				<label for="user"><?=@text( 'User' )?></label>
				<div>
					<?= @helper('com://admin/users.template.helper.listbox.users',
							array(
								'autocomplete' => true,
								'name'		   => 'user',
								'validate'     => false,
								'attribs'      => array('size' => 30),
							)) ?>
				</div>
	
				<div class="btn-group">
					<input type="submit" name="submitfilter" class="btn" value="<?=@text('Filter')?>" />
					<input type="reset" name="cancelfilter" class="btn" value="<?=@text('Reset')?>" />
				</div>
			</div>
		</fieldset>
	</form>
</div>
