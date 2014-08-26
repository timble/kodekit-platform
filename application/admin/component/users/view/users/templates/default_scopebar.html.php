<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null(state()->enabled) && is_null(state()->visited) && is_null(state()->authentic) ? 'active' : ''; ?>" href="<?= route('enabled=&authentic=&visited=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= state()->enabled === true ? 'active' : ''; ?>" href="<?= route(state()->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= translate('Enabled') ?>
		</a> 
		<a class="<?= state()->enabled === false ? 'active' : ''; ?>" href="<?= route(state()->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= translate('Disabled') ?>
		</a> 
	</div>
	<div class="scopebar__group">
		<a class="<?= state()->authentic === true ? 'active' : ''; ?>" href="<?= route( state()->authentic === true ? 'authentic=' : 'authentic=1&visited=' ) ?>">
		    <?= translate('Logged in') ?>
		</a> 
		<a class="<?= state()->visited === false ? 'active' : ''; ?>" href="<?= route( state()->visited === false? 'visited=' : 'visited=0&authentic=' ) ?>">
		    <?= translate('Never visited') ?>
		</a> 
	</div>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>