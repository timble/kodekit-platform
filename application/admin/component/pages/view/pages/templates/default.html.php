<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->
<?= helper('behavior.sortable', array('options' => array('nested' => true/*, 'adapter' => array('options' => array('key' => 'custom'))*/))) ?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<ktml:module position="sidebar">
    <?= import('default_sidebar.html') ?>
</ktml:module>

<form id="pages-form" action="" method="get" class="-koowa-grid" >
    <?= import('default_scopebar.html') ?>
    <table>
        <thead>
            <tr>
                <? if($state->sort == 'custom' && $state->direction == 'asc') : ?><th class="handle"></th><? endif ?>
                <th width="1">
                    <?= helper('grid.checkall'); ?>
                </th>
                <th width="1"></th>
                <th>
                    <?= helper('grid.sort', array('column' => 'title')); ?>
                </th>
                <th width="1">
                    <?= helper('grid.sort',  array('column' => 'custom' , 'title' => 'Ordering')); ?>
                </th>
                <th width="1">
                    <?= helper('grid.sort',  array('column' => 'extensions_extension_id' , 'title' => 'Type')); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>

        <tbody class="sortable">
        <? $tbody = null; foreach($pages as $page) : ?>
            <? if(!$page->getParentId() && $page->getParentId() != $tbody) $tbody = $page->getParentId(); ?>
            <tr class="sortable" data-sortable-parent="<?= (int)$page->getParentId() ?>" data-sortable-level="<?= (int)$page->level ?>">
                <? if($state->sort == 'custom' && $state->direction == 'asc') : ?>
                    <td class="handle">
                        <span class="text-small data-order"><?= $page->ordering ?></span>
                    </td>
                <? endif ?>
                <td align="center">
                    <?= helper('grid.checkbox',array('row' => $page)); ?>
                </td>
                <td align="center">
                    <?= helper('grid.enable', array('row' => $page, 'field' => 'published')) ?>
                </td>
                <td>
                    <?
                        $link = 'type[name]='.$page->type;
                        if($page->type == 'component')
                        {
                            $link .= '&type[option]='.$page->getLink()->query['option'].'&type[view]='.$page->getLink()->query['view'];
                            $link .= '&type[layout]='.(isset($page->getLink()->query['layout']) ? $page->getLink()->query['layout'] : 'default');
                        }

                        $link .= '&view=page&menu='.$state->menu.'&id='.$page->id;
                    ?>
                    <a href="<?= urldecode(route($link)) ?>">
                        <? if($page->level > 1) : ?>
                            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - 1) ?><sup>|_</sup>&nbsp;
                        <? endif ?>
                        <?= escape($page->title) ?>
                    </a>
                    <? if($page->home) : ?>
                        <i class="icon-star"></i>
                    <? endif ?>
                    <? if($page->access) : ?>
                        <span class="label label-important"><?= translate('Registered') ?></span>
                    <? endif; ?>
                    <? if($page->hidden) : ?>
                        <span class="label label-info"><?= translate('Hidden') ?></span>
                    <? endif; ?>
                </td>
                <td align="center">
                    <?= helper('grid.order', array('row'=> $page, 'total' => $total)) ?>
                </td>
                <td>
                    <?= $page->getTypeDescription() ?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</form>
