<?
/**
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<nav class="scrollable">
	<a class="<?= !is_numeric($state->category) ? 'active' : ''; ?>" href="<?= @route('category=' ) ?>">
	    <?= @text('All articles')?>
	</a>

	<a class="<?= $state->category == '0' ? 'active' : ''; ?>" href="<?= @route('&category=0' ) ?>">
		<?= @text('Uncategorised') ?>
	</a>
	<? foreach($categories as $category) : ?>
	<a class="<?= $state->category == $category->id ? 'active' : ''; ?>" href="<?= @route('category='.$category->id ) ?>">
		<?= @escape($category->title) ?>
	</a>
	<? if($category->hasChildren()) : ?>
		<? foreach($category->getChildren() as $child) : ?>
			<a style="padding-left: 36px;" class="<?= $state->category == $child->id ? 'active' : ''; ?>" href="<?= @route('sort=ordering&category='.$child->id ) ?>">
				<?= $child->title; ?>
			</a>
		<? endforeach ?>
	<? endif; ?>
	<? endforeach ?>
</nav>