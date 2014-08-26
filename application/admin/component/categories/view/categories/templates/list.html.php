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
        <a class="<?= state()->category == null ? 'active' : ''; ?>" href="<?= route('category=' ) ?>">
            <?= translate('All categories') ?>
        </a>
	</li>
	<? foreach ($categories as $category) : ?>
	<li>
        <a class="<?= state()->category == $category->id ? 'active' : ''; ?>" href="<?= route('category='.$category->id ) ?>">
            <?= escape($category->title) ?>
        </a>
	</li>
	<? endforeach ?>
</ul>