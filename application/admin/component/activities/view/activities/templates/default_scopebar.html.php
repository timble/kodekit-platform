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
		<a class="<?= is_null(parameters()->action) && is_null(parameters()->application) ? 'active' : ''; ?>" href="<?= route('application=&action=' ) ?>">
		    <?= translate('All') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a  class="<?= (parameters()->action == 'add') ? 'active' : ''; ?> separator-left" href="<?= route('action=add' ) ?>">
		    <?= translate('Created') ?>
		</a>
		<a class="<?= (parameters()->action == 'edit') ? 'active' : ''; ?>" href="<?= route('action=edit' ) ?>">
		    <?= translate('Updated') ?>
		</a>
		<a class="<?= (parameters()->action == 'delete') ? 'active' : ''; ?>" href="<?= route('action=delete' ) ?>">
		    <?= translate('Trashed') ?>
		</a>
	</div>
	<div class="scopebar__group">
		<a class="<?= (parameters()->direction == 'desc') ? 'active' : ''; ?>" href="<?= route(parameters()->direction == 'desc' ? 'direction=' : 'direction=desc' ) ?>">
		    <?= translate('Latest First') ?>
		</a>
		<a class="<?= (parameters()->direction == 'asc') ? 'active' : ''; ?>" href="<?= route(parameters()->direction == 'asc' ? 'direction=' : 'direction=asc' ) ?>">
		    <?= translate('Oldest First') ?>
		</a>
	</div>
	</ul>
</div>