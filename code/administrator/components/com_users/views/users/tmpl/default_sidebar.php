<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' );

?>

<div id="sidebar">
	<h3><?= @text('Groups') ?></h3>
	<ul>
		<li class="<?= !is_numeric($state->group) ? 'active' : ''; ?>">
			<a href="<?= @route('group=' ) ?>">
			    <?= @text('All users')?>
			</a>
		</li>
		<? foreach($groups as $group) : ?>
            <? if($group->depth) : ?>
                <li <?= $state->group == $group->id ? 'class="active"' : '' ?>>
                    <a href="<?= @route('group='.$group->id) ?>">
                        <?= $group->name ?>
                    </a>
                </li>
            <? endif ?>
		<? endforeach ?>
	</ul>
</div>