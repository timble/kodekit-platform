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
		<a class="<?= is_null($state->enabled) && is_null($state->visited) && is_null($state->loggedin) ? 'active' : ''; ?>" href="<?= route('enabled=&loggedin=&visited=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->enabled === true ? 'active' : ''; ?>" href="<?= route($state->enabled === true ? 'enabled=' : 'enabled=1' ) ?>">
		    <?= translate('Enabled') ?>
		</a> 
		<a class="<?= $state->enabled === false ? 'active' : ''; ?>" href="<?= route($state->enabled === false ? 'enabled=' : 'enabled=0' ) ?>">
		    <?= translate('Disabled') ?>
		</a> 
	</div>
	<div class="scopebar__group">
		<a class="<?= $state->loggedin === true ? 'active' : ''; ?>" href="<?= route( $state->loggedin === true ? 'loggedin=' : 'loggedin=1&visited=' ) ?>">
		    <?= translate('Logged in') ?>
		</a> 
		<a class="<?= $state->visited === false ? 'active' : ''; ?>" href="<?= route( $state->visited === false? 'visited=' : 'visited=0&loggedin=' ) ?>">
		    <?= translate('Never visited') ?>
		</a> 
	</div>
	<div class="scopebar__search">
		<?= helper('grid.search') ?>
	</div>
</div>