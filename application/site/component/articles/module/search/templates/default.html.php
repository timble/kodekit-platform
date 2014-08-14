<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<? if ($module->getParameters()->get('item_id', null)): ?>
    <form action="<?= route('component=articles&view=articles&Itemid=' . $module->getParameters()->get('item_id')) ?>" method="get" class="navbar-form pull-right">
        <div class="form-group">
            <input id="search" name="search" class="form-control" type="text" value="" placeholder="<?= translate('Search articles') ?>"/>
        </div>
        <button type="submit" class="btn btn-default"><?= translate('Submit') ?></button>
    </form>
<? endif; ?>