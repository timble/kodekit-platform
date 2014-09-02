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