<?php 
/**
 * @version     $Id$
 * @section	Nooku
 * @package     Nooku_Server
 * @subpackage  Sections
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<ul>
	<li class="<?= $state->parent == null ? 'active' : ''; ?>">
		<a href="<?= @route('parent=&section=com_content' ) ?>">
			<?= @text('All sections') ?>
		</a>
	</li>
	<? foreach ($sections as $section) : ?>
	<li class="<?= $state->parent == $section->id ? 'active' : ''; ?>">
		<a href="<?= @route('parent='.$section->id.'&section=com_content' ) ?>">
			<?= @escape($section->title) ?>
		</a>
	</li>
	<? endforeach ?>
</ul>