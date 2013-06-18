<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null($state->enabled) && is_null($state->visited) && is_null($state->loggedin) ? 'active' : ''; ?>" href="<?= @route('enabled=&loggedin=&visited=' ) ?>">
		    <?= @text('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->enabled === true ? 'active' : ''; ?>" href="<?= @route($state->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= @text('Enabled') ?>
		</a> 
		<a class="<?= $state->enabled === false ? 'active' : ''; ?>" href="<?= @route($state->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= @text('Disabled') ?>
		</a> 
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->loggedin === true ? 'active' : ''; ?>" href="<?= @route( $state->loggedin === true ? 'loggedin=' : 'loggedin=1&visited=' ) ?>">
		    <?= @text('Logged in') ?>
		</a> 
		<a class="<?= $state->visited === false ? 'active' : ''; ?>" href="<?= @route( $state->visited === false? 'visited=' : 'visited=0&loggedin=' ) ?>">
		    <?= @text('Never visited') ?>
		</a> 
	</div>
	<div class="scopebar__search">
		<?= @helper('grid.search') ?>
	</div>
</div>