<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>
<?= helper('com:theme.behavior.sortable') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<? if($articles->isTranslatable()) : ?>
    <ktml:block extend="actionbar">
        <?= helper('com:languages.listbox.languages') ?>
    </ktml:block>
<? endif ?>

<ktml:block prepend="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:block>

<form action="" method="get" class="-koowa-grid">
    <?= import('default_scopebar.html'); ?>
    <table>
        <thead>
        <tr>
            <? if($sortable) : ?>
                <th class="handle"></th>
            <? endif ?>
            <th width="1">
                <?= helper('grid.checkall') ?>
            </th>
            <th width="1"></th>
            <th>
                <?= helper('grid.sort', array('column' => 'title')) ?>
            </th>
            <th width="1">
                <?= helper('grid.sort', array('title' => 'Last modified', 'column' => 'modified_on')) ?>
            </th>
            <? if($articles->isTranslatable()) : ?>
                <th width="70">
                    <?= translate('Translation') ?>
                </th>
            <? endif ?>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="7">
                <?= helper('com:theme.paginator.pagination') ?>
            </td>
        </tr>
        </tfoot>
        <tbody<?= $sortable ? ' class="sortable"' : '' ?>>
        <? foreach($articles as $article) : ?>
            <tr data-readonly="<?= $article->getStatus() == 'deleted' ? '1' : '0' ?>">
                <? if($sortable) : ?>
                    <td class="handle">
                        <span class="text--small data-order"><?= $article->ordering ?></span>
                    </td>
                <? endif ?>
                <td align="center">
                    <?= helper('grid.checkbox' , array('entity' => $article)) ?>
                </td>
                <td align="center">
                    <?= helper('grid.enable', array('entity' => $article, 'field' => 'published')) ?>
                </td>
                <td class="ellipsis">
                    <?if($article->getStatus() != 'deleted') : ?>
                        <a href="<?= route('view=article&id='.$article->id) ?>">
                            <?= escape($article->title) ?>
                        </a>
                    <? else : ?>
                        <?= escape($article->title); ?>
                    <? endif; ?>
                    <? if($article->access) : ?>
                        <span class="label label-important"><?= translate('Registered') ?></span>
                    <? endif; ?>
                </td>
                <td>
                    <?= helper('date.humanize', array('date' => $article->modified_on)) ?> by <a href="<?= route('component=users&view=user&id='.$article->created_by) ?>">
                        <?= $article->getEditor()->getName() ?>
                    </a>
                </td>
                <? if($article->isTranslatable()) : ?>
                    <td>
                        <?= helper('com:languages.grid.status', array(
                            'status'   => $article->translation_status,
                            'original' => $article->translation_original,
                            'deleted'  => $article->translation_deleted));
                        ?>
                    </td>
                <? endif ?>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>