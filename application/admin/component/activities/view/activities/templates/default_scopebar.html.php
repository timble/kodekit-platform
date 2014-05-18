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
		<a class="<?= is_null($state->action) && is_null($state->application) ? 'active' : ''; ?>" href="<?= route('application=&action=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a  class="<?= ($state->action == 'add') ? 'active' : ''; ?> separator-left" href="<?= route('action=add' ) ?>">
		    <?= translate('Created') ?>
		</a>
		<a class="<?= ($state->action == 'edit') ? 'active' : ''; ?>" href="<?= route('action=edit' ) ?>">
		    <?= translate('Updated') ?>
		</a>
		<a class="<?= ($state->action == 'delete') ? 'active' : ''; ?>" href="<?= route('action=delete' ) ?>">
		    <?= translate('Trashed') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= ($state->direction == 'desc') ? 'active' : ''; ?>" href="<?= route($state->direction == 'desc' ? 'direction=' : 'direction=desc' ) ?>">
		    <?= translate('Latest First') ?>
		</a>
		<a class="<?= ($state->direction == 'asc') ? 'active' : ''; ?>" href="<?= route($state->direction == 'asc' ? 'direction=' : 'direction=asc' ) ?>">
		    <?= translate('Oldest First') ?>
		</a>
	</div>
	</ul>
</div>