<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

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

<div id="sidebar">
	<h3><?=@text( 'Components' )?></h3>
	<ul>
		<li class="<?= empty($state->package) ? 'active' : ''; ?>">
			<a href="<?= @route('package=') ?>">
		    <?= @text('All components')?>
			</a>
		</li>
	    <?php foreach ($packages as $package): ?>
		    <?php if ($package->id == $state->package): ?>
				<li class="active">
		    <?php else: ?> <li> <?php endif ?>
				<a href="<?=@route('package='.$package->id)?>"><?=ucfirst($package->package)?></a>
			</li>	
	    <?php endforeach ?>
	</ul>
	
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
					<?= @helper('com://admin/users.template.helper.autocomplete.users', 
							array(
								'column' => 'user', 
								'value' => $state->user, 
								'validate' => false,
								'attribs' => array('size' => 30)
							)) ?>
				</div>
				
				<div class="activities-buttons">
					<input type="submit" name="submitfilter" class="btn" value="<?=@text('Filter')?>" />
					<input type="reset" name="cancelfilter" class="btn" value="<?=@text('Reset')?>" />
				</div>
			</fieldset>
		</form>
	</div>

	<h3><?=@text( 'Status' )?></h3>
	<div>
	</div>
</div>
