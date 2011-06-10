<?php 
/**
 * @version     $Id: list.php 1460 2011-05-24 11:55:42Z tomjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar" class="-koowa-box-scroll">
	<h3><?= @text('Folders')?></h3>
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
		<? foreach($folders->find(array('parent_id' => 0)) as $section) : ?>
		<li class="<?= $state->section == $section->id ? 'active' : ''; ?>">
			<a href="<?= @route('section='.$section->id.'&category=' ) ?>">
				<?= @escape($section->title) ?>
			</a>
			<ul>
				<? foreach($folders->find(array('parent_id' => $section->id)) as $category) : ?>
				<li class="<?= $state->category == $category->id ? 'active' : ''; ?>">
					<a href="<?= @route('section=&category='.$category->id ) ?>">
						<?= $category->title; ?>
					</a>
				</li>
				<? endforeach ?>
			</ul>
		</li>
		<? endforeach ?>
	</ul>
</div>