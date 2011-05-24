<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<ul>
	<li class="<?= $state->category == null ? 'active' : ''; ?>">
		<a href="<?= @route('category=' ) ?>">
			<?= 'All categories' ?>
		</a>
	</li>
	<? foreach ($categories as $category) : ?>
	<li class="<?= $state->category == $category->id ? 'active' : ''; ?>">
		<a href="<?= @route('category='.$category->id ) ?>">
			<?= @escape($category->title) ?>
		</a>
	</li>
	<? endforeach ?>
</ul>