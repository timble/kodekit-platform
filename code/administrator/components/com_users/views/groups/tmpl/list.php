<?
/**
 * @version     $Id: list.php 4523 2012-08-11 18:30:46Z johanjanssens $
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<nav class="scrollable">
	<a class="<?= !is_null($state->group) ? 'active' : ''; ?>" href="<?= @route('group=') ?>">
	    <?= @text('All groups') ?>
	</a>
	
	<h4><?= @text('System') ?></h4>
	<? foreach($groups->find(array('type' => 'system')) as $group) : ?>
        <a <?= $state->group == $group->id ? 'class="active"' : '' ?> href="<?= @route('group='.$group->id) ?>">
            <?= $group->name ?>
        </a>
	<? endforeach ?>
	
	<h4><?= @text('Custom') ?></h4>
	<? foreach($groups->find(array('type' => 'custom')) as $group) : ?>
        <a <?= $state->group == $group->id ? 'class="active"' : '' ?> href="<?= @route('group='.$group->id) ?>">
            <?= $group->name ?>
        </a>
	<? endforeach ?>
</nav>