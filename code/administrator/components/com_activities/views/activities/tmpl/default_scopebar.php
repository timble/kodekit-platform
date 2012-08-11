<?
/**
 * @version     $Id: default_filter.php 1681 2012-05-08 10:17:43Z tomjanssens $
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Activities
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<div class="scopebar">
	<div class="scopebar-group">
		<a class="<?= is_null($state->action) && is_null($state->application) ? 'active' : ''; ?>" href="<?= @route('application=&action=' ) ?>">
		    <?= @text('All') ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a  class="<?= ($state->action == 'add') ? 'active' : ''; ?> separator-left" href="<?= @route('action=add' ) ?>">
		    <?= @text('Created') ?>
		</a>
		<a class="<?= ($state->action == 'edit') ? 'active' : ''; ?>" href="<?= @route('action=edit' ) ?>">
		    <?= @text('Updated') ?>
		</a>
		<a class="<?= ($state->action == 'delete') ? 'active' : ''; ?>" href="<?= @route('action=delete' ) ?>">
		    <?= @text('Trashed') ?>
		</a>
	</div>
	<div class="scopebar-group">
		<a class="<?= ($state->direction == 'desc') ? 'active' : ''; ?>" href="<?= @route($state->direction == 'desc' ? 'direction=' : 'direction=desc' ) ?>">
		    <?= @text('Latest First') ?>
		</a>
		<a class="<?= ($state->direction == 'asc') ? 'active' : ''; ?>" href="<?= @route($state->direction == 'asc' ? 'direction=' : 'direction=asc' ) ?>">
		    <?= @text('Oldest First') ?>
		</a>
	</div>
	</ul>
</div>