<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null(parameters()->enabled) && is_null(parameters()->visited) && is_null(parameters()->authentic) ? 'active' : ''; ?>" href="<?= route('enabled=&authentic=&visited=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameters()->enabled === true ? 'active' : ''; ?>" href="<?= route(parameters()->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= translate('Enabled') ?>
		</a>
		<a class="<?= parameters()->enabled === false ? 'active' : ''; ?>" href="<?= route(parameters()->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= translate('Disabled') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameters()->authentic === true ? 'active' : ''; ?>" href="<?= route( parameters()->authentic === true ? 'authentic=' : 'authentic=1&visited=' ) ?>">
		    <?= translate('Logged in') ?>
		</a>
		<a class="<?= parameters()->visited === false ? 'active' : ''; ?>" href="<?= route( parameters()->visited === false? 'visited=' : 'visited=0&authentic=' ) ?>">
		    <?= translate('Never visited') ?>
		</a>
	</div>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>