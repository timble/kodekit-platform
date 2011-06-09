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

<div id="filter" class="group">
	<ul>
		<li class="<?= $state->state == null ? 'active' : ''; ?>">
			<a href="<?= @route('state=' ) ?>">
			    <?= 'All' ?>
			</a>
		</li>
		<li class="<?= $state->state == '1' ? 'active' : ''; ?>">
			<a href="<?= @route('state=1' ) ?>">
			    <?= 'Published' ?>
			</a> 
		</li>
		<li class="<?= $state->state == '0' ? 'active' : ''; ?>">
			<a href="<?= @route('state=0' ) ?>">
			    <?= 'Drafts' ?>
			</a> 
		</li>
		<li class="<?= $state->state == '-1' ? 'active' : ''; ?>">
			<a href="<?= @route('state=-1' ) ?>">
			    <?= 'Archived' ?>
			</a> 
		</li>
	</ul>
</div>