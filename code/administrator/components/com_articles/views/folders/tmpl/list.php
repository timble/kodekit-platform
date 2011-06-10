<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<ul>
	<li class="<?= !is_numeric($state->section) && !is_numeric($state->category) ? 'active' : ''; ?>">
		<a href="<?= @route('section=&category=' ) ?>">
		    <?= @text('All articles')?>
		</a>
	</li>
	<li class="<?= $state->category == '0' && $state->category == '0' ? 'active' : ''; ?>">
		<a href="<?= @route('section=0&category=0' ) ?>">
			<?= @text('Uncategorised') ?>
		</a>
	</li>
	<? foreach($folders as $folder) : ?>
	<li class="<?= $state->section == $folder->id ? 'active' : ''; ?>">
		<a href="<?= @route('section='.$folder->id.'&category=' ) ?>">
			<?= @escape($folder->title) ?>
		</a>
		<? if($folder->hasChildren()) : ?>
		<ul>
			<? foreach($folder->getChildren() as $child) : ?>
			<li class="<?= $state->category == $child->id ? 'active' : ''; ?>">
				<a href="<?= @route('section=&category='.$child->id ) ?>">
					<?= $child->title; ?>
				</a>
			</li>
			<? endforeach ?>
		</ul>
		<? endif; ?>
	</li>
	<? endforeach ?>
</ul>