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

<div id="sidebar">
	<h3><?= @text('Filters')?></h3>
	<ul>
		<li class="<?= $state->featured !== true ? 'active' : ''; ?>">
			<a href="<?= @route('featured=' ) ?>">
			    <?= 'All articles' ?>
			</a>
		</li>
		<li class="<?= $state->featured == true ? 'active' : ''; ?>">
			<a href="<?= @route('featured=1' ) ?>">
			    <?= 'Featured' ?>
			</a> 
		</li>
	</ul>
</div>