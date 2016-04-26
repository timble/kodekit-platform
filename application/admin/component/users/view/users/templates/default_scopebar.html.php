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
		<a class="<?= is_null(parameter('enabled')) && is_null(parameter('visited')) && is_null(parameter('authentic')) ? 'active' : ''; ?>" href="<?= route('enabled=&authentic=&visited=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameter('enabled') === true ? 'active' : ''; ?>" href="<?= route(parameter('enabled') === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= translate('Enabled') ?>
		</a>
		<a class="<?= parameter('enabled') === false ? 'active' : ''; ?>" href="<?= route(parameter('enabled') === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= translate('Disabled') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= parameter('authentic') === true ? 'active' : ''; ?>" href="<?= route( parameter('authentic') === true ? 'authentic=' : 'authentic=1&visited=' ) ?>">
		    <?= translate('Logged in') ?>
		</a>
		<a class="<?= parameter('visited') === false ? 'active' : ''; ?>" href="<?= route( parameter('visited') === false? 'visited=' : 'visited=0&authentic=' ) ?>">
		    <?= translate('Never visited') ?>
		</a>
	</div>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>