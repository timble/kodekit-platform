<?php 
/**
 * @version     $Id: list.php 1460 2011-05-24 11:55:42Z tomjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="sidebar" class="-koowa-box-scroll">
	<h3><?= @text('Filters')?></h3>
	<ul>
		<li class="<?= $state->featured == false && !is_numeric($state->section) && !is_numeric($state->category) ? 'active' : ''; ?>">
			<a href="<?= @route('featured=&section=&category=' ) ?>">
			    <?= 'All articles' ?>
			</a>
		</li>
		<li class="<?= $state->featured == true && !is_numeric($state->section) && !is_numeric($state->category) ? 'active' : ''; ?>">
			<a href="<?= @route('featured=1&section=&category=' ) ?>">
			    <?= 'Featured' ?>
			</a> 
		</li>
	</ul>
	<h3><?= @text('Folders')?></h3>
	<ul>
		<? foreach($folders->find(array('parent_id' => 0)) as $section) : ?>
		<li class="<?= $state->section == $section->id ? 'active' : ''; ?>">
			<a href="<?= @route('featured=&section='.$section->id.'&category=' ) ?>">
				<?= @escape($section->title) ?>
			</a>
			<ul>
				<? foreach($folders->find(array('parent_id' => $section->id)) as $category) : ?>
				<li class="<?= $state->category == $category->id ? 'active' : ''; ?>">
					<a href="<?= @route('featured=&section=&category='.$category->id ) ?>">
						<?= $category->title; ?>
					</a>
				</li>
				<? endforeach ?>
			</ul>
		</li>
		<? endforeach ?>
	</ul>
</div>