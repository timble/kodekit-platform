<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright      Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link           https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>
<?= helper('com:application.behavior.sortable', array(
    'options' => array('nested' => true /*, 'adapter' => array('options' => array('key' => 'custom'))*/)
)) ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<ktml:block prepend="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:block>

<form id="pages-form" action="" method="get" class="-koowa-grid">
    <?= import('default_scopebar.html') ?>
    <table>
        <thead>
        <tr>
            <? if (parameters()->sort == 'custom' && parameters()->direction == 'asc') : ?>
                <th class="handle"></th><? endif ?>
            <th width="1">
                <?= helper('grid.checkall'); ?>
            </th>
            <th width="1"></th>
            <th>
                <?= helper('grid.sort', array('column' => 'title')); ?>
            </th>
            <th width="1">
                <?= helper('grid.sort', array('column' => 'custom', 'title' => 'Ordering')); ?>
            </th>
            <th width="1">
                <?= helper('grid.sort', array('column' => 'component', 'title' => 'Type')); ?>
            </th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="6">
                <?= helper('com:application.paginator.pagination') ?>
            </td>
        </tr>
        </tfoot>

        <tbody class="sortable">
        <? foreach ($pages as $page) : ?>
            <tr class="sortable" data-sortable-parent="<?= (int)$page->parent_id; ?>"
                data-sortable-level="<?= (int)$page->level ?>">
                <? if (parameters()->sort == 'custom' && parameters()->direction == 'asc') : ?>
                    <td class="handle">
                        <span class="text--small data-order"><?= $page->ordering ?></span>
                    </td>
                <? endif ?>
                <td align="center">
                    <?= helper('grid.checkbox', array('entity' => $page)); ?>
                </td>
                <td align="center">
                    <?= helper('grid.enable', array('entity' => $page, 'field' => 'published')) ?>
                </td>
                <td>
                    <?/*
                    $link  = '&type[component]=' . $page->getLink()->query['component'] . '&type[view]=' . $page->getLink()->query['view'];
                    $link .= '&type[layout]=' . (isset($page->getLink()->query['layout']) ? $page->getLink()->query['layout'] : 'default');
                    $link .= '&view=page&menu=' . parameters()->menu . '&id=' . $page->id;
                    */?>

                    <a href="<?= route('view=page&menu='.parameters()->menu.'&id='.$page->id) ?>">
                        <? if ($page->level > 1) : ?>
                            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - 1) ?><sup>|_</sup>&nbsp;
                        <? endif ?>
                        <?= escape($page->title) ?>
                    </a>
                    <? if ($page->default) : ?>
                        <i class="icon-star"></i>
                    <? endif ?>
                    <? if ($page->access) : ?>
                        <span class="label label-important"><?= translate('Registered') ?></span>
                    <? endif; ?>
                    <? if ($page->hidden) : ?>
                        <span class="label label-info"><?= translate('Hidden') ?></span>
                    <? endif; ?>
                </td>
                <td align="center">
                    <?= helper('grid.order', array('entity' => $page)) ?>
                </td>
                <td>
                    <?= $page->getDescription() ?>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</form>
