<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<? if (is_numeric(parameters()->item_id)): ?>

    <? if(parameters()->show_title) : ?>
        <h3><?= title() ?></h3>
    <? endif ?>

    <form action="<?= route('component=articles&view=articles&Itemid=' . parameters()->item_id) ?>" method="get" class="navbar-form pull-right">
        <div class="form-group">
            <input id="search" name="search" class="form-control" type="text" value="" placeholder="<?= translate('Search articles') ?>"/>
        </div>
        <button type="submit" class="btn btn-default"><?= translate('Submit') ?></button>
    </form>

<? endif; ?>