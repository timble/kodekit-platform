<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright      Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? if ($module->getParameters()->get('item_id', null)): ?>
    <form action="<?= route('option=com_articles&view=articles&Itemid=' . $module->getParameters()->get('item_id')) ?>" method="get" class="navbar-search form-search pull-right">
        <div class="input-append">
            <input id="search" name="search" class="pan2 search-query" type="text" value="" placeholder="<?= translate('Search articles') ?>"/>
            <button type="submit" class="btn"><i class="icon-search"></i></button>
        </div>
    </form>
<? endif; ?>