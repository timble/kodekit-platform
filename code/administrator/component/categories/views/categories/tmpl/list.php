<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<nav class="scrollable">
	<a class="<?= $state->category == null ? 'active' : ''; ?>" href="<?= @route('category=' ) ?>">
		<?= 'All categories' ?>
	</a>
	<? foreach ($categories as $category) : ?>
	<a class="<?= $state->category == $category->id ? 'active' : ''; ?>" href="<?= @route('category='.$category->id ) ?>">
		<?= @escape($category->title) ?>
	</a>
	<? endforeach ?>
</nav>