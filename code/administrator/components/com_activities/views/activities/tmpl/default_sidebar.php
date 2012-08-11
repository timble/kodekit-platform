<?php
/**
 * @version     $Id$
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
	
	<div class="activities-filter">
		<h3><?=@text( 'Filters' )?></h3>
	
		<form action="" method="get" id="activities-filter">
			<fieldset>
				<h4><?=@text( 'Start Date' )?></h4>
				<div class="activities-calendar">
					<?= @helper('behavior.calendar',
							array(
								'date' => $state->start_date,
								'name' => 'start_date',
								'format' => '%Y-%m-%d'
							)); ?>
				</div>
	
				<h4><?=@text( 'Days Back' )?></h4>
				<div class="activities-days-back">
					<input type="text" size="3" name="days_back" value="<?=($state->days_back) ? $state->days_back : '' ?>" />
				</div>
	
				<h4><?=@text( 'User' )?></h4>
				<div>
					<?= @helper('com://admin/users.template.helper.listbox.users',
							array(
								'autocomplete' => true,
								'name'		   => 'user',
								'validate'     => false,
								'attribs'      => array('size' => 30),
							)) ?>
				</div>
	
				<div class="activities-buttons">
					<input type="submit" name="submitfilter" class="btn" value="<?=@text('Filter')?>" />
					<input type="reset" name="cancelfilter" class="btn" value="<?=@text('Reset')?>" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
