<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Logs
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access') ?>

<?= @helper('behavior.validator') ?>

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
	
	<div class="logs-filter">
		<h3><?=@text( 'Time Filter' )?></h3>

		<form action="" method="get" name="logs-time-filter">
			<fieldset>
				<h4><?=@text( 'Start Date' )?></h4>
				<div class="logs-calendar">
					<?= @helper('behavior.calendar', array('date' => $state->start_date, 'name' => 'start_date')); ?>
				</div>

				<h4><?=@text( 'Days Back' )?></h4>
				<div class="logs-days-back">
					<input type="text" size="3" name="days_back" value="<?=($state->days_back) ? $state->days_back : 14?>" />
				</div>
				<div class="logs-buttons">
					<input type="reset" name="cancelfilter" value="<?=@text('Reset')?>" />
					<input type="submit" name="submitfilter" value="<?=@text('Filter')?>" />
				</div>
			</fieldset>
		</form>
	</div>

	<div class="logs-filter">
		<h3><?=@text( 'Users' )?></h3>

		<form action="" method="get" name="logs-user-filter">
			<fieldset>
				<div>
					<?= @helper('com://admin/users.template.helper.autocomplete.users', 
							array(
								'column' => 'user', 
								'value' => $state->actor, 
								'validate' => false,
								'attribs' => array('size' => 30)
							)) ?>
				</div>
				<div class="logs-buttons">
					<input type="submit" name="submitfilter" value="<?=@text('Filter')?>" />
				</div>
			</fieldset>
		</form>
	</div>
</div>
