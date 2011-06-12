<?php
/**
 * @version     $Id: default_filter.php 1696 2011-06-10 16:00:55Z tomjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Newsfeeds
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<div id="filter" class="group">
	<ul>
		<li class="<?= !is_numeric($state->published) ? 'active' : '' ?> separator-right">
			<a href="<?= @route('published=') ?>">
			    <?= @text('All') ?>
			</a>
		</li>
		<li class="<?= $state->published == '1' ? 'active' : ''; ?>">
			<a href="<?= @route('published=1' ) ?>">
			    <?= @text('Published') ?>
			</a>
		</li>
		<li class="<?= $state->published == '0' ? 'active' : ''; ?>">
			<a href="<?= @route('published=0' ) ?>">
			    <?= @text('Unpublished') ?>
			</a>
		</li>
	</ul>
</div>