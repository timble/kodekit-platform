<?php
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
<?= helper('behavior.sortable') ?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<? if($articles->isTranslatable()) : ?>
    <ktml:module position="actionbar" content="append">
        <?= helper('com:languages.listbox.languages') ?>
    </ktml:module>
<? endif ?>

<ktml:module position="sidebar">
    <?= import('default_sidebar.html'); ?>
</ktml:module>

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
                    <?= helper('grid.sort', array('title' => 'Last modified', 'column' => 'last_activity_on')) ?>
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
                    <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody<?= $sortable ? ' class="sortable"' : '' ?>>
        <? foreach($articles as $article) : ?>
            <tr data-readonly="<?= $article->getStatus() == 'deleted' ? '1' : '0' ?>">
                <? if($sortable) : ?>
                <td class="handle">
                    <span class="text-small data-order"><?= $article->ordering ?></span>
                </td>
                <? endif ?>
                <td align="center">
                    <?= helper('grid.checkbox' , array('row' => $article)) ?>
                </td>
                <td align="center">
                    <?= helper('grid.enable', array('row' => $article, 'field' => 'published')) ?>
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
                    <?= helper('date.humanize', array('date' => $article->last_activity_on)) ?> by <a href="<?= route('option=com_users&view=user&id='.$article->created_by) ?>">
                        <?= $article->last_activity_by_name ?>
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