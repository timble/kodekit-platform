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
	<? foreach($categories as $category) : ?>
	<li class="<?= $state->section == $category->id ? 'active' : ''; ?>">
		<a href="<?= @route('section='.$category->id.'&category=' ) ?>">
			<?= @escape($category->title) ?>
		</a>
		<? if($category->hasChildren()) : ?>
		<ul>
			<? foreach($category->getChildren() as $child) : ?>
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