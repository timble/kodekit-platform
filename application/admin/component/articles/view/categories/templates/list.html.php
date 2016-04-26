<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ul class="navigation">
    <li>
        <a class="<?= !is_numeric(parameter('category')) ? 'active' : ''; ?>" href="<?= route('category=' ) ?>">
            <?= translate('All articles')?>
        </a>
    </li>
    <li>
        <a class="<?= parameter('category') == '0' ? 'active' : ''; ?>" href="<?= route('category=0' ) ?>">
            <?= translate('Uncategorised') ?>
        </a>
    </li>

    <? foreach($categories as $category) : ?>
    <li>
        <a class="<?= parameter('category') == $category->id ? 'active' : ''; ?>" href="<?= route('category='.$category->id ) ?>">
            <?= escape($category->title) ?>
        </a>
    </li>

    <? endforeach ?>
</ul>