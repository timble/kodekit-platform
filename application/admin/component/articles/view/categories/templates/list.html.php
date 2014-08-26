<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ul class="navigation">
	<li>
        <a class="<?= !is_numeric(state()->category) ? 'active' : ''; ?>" href="<?= route('category=' ) ?>">
            <?= translate('All articles')?>
        </a>
	</li>
	<li>
        <a class="<?= state()->category == '0' ? 'active' : ''; ?>" href="<?= route('&category=0' ) ?>">
            <?= translate('Uncategorised') ?>
        </a>
	</li>

    <? foreach($categories as $category) : ?>
	<li>
        <a class="<?= state()->category == $category->id ? 'active' : ''; ?>" href="<?= route('category='.$category->id ) ?>">
            <?= escape($category->title) ?>
        </a>

        <? if($category->hasChildren()) : ?>
        <ul>
            <? foreach($category->getChildren() as $child) : ?>
            <li>
                <a class="<?= state()->category == $child->id ? 'active' : ''; ?>" href="<?= route('sort=ordering&category='.$child->id ) ?>">
                    <?= $child->title; ?>
                </a>
            </li>
            <? endforeach ?>
        </ul>
        <? endif; ?>
    </li>

	<? endforeach ?>
</ul>