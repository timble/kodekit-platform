<?php 
/**
 * @version     $Id: default_filter.php 3472 2012-03-17 14:57:58Z tomjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Plugins
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div class="scopebar">
	<div class="scopebar-group">
		<a class="<?= is_null($state->enabled) ? 'active' : ''; ?>" href="<?= @route('enabled=' ) ?>">
		    <?= @text('All') ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= $state->enabled === true ? 'active' : ''; ?>" href="<?= @route($state->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= @text('Enabled') ?>
		</a> 
		<a class="<?= $state->enabled === false ? 'active' : ''; ?>" href="<?= @route($state->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= @text('Disabled') ?>
		</a> 
	</div>
	<div class="scopebar-search">
		<?= @helper('grid.search') ?>
	</div>
</div>