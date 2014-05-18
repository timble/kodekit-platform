<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<div class="scopebar">
	<div class="scopebar__group">
		<a class="<?= is_null($state->enabled) ? 'active' : '' ?>" href="<?= route('enabled=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->enabled === true ? 'active' : '' ?>" href="<?= route($state->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= translate('Enabled') ?>
		</a> 
		<a class="<?= $state->enabled === false ? 'active' : '' ?>" href="<?= route($state->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= translate('Disabled') ?>
		</a> 
	</div>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>