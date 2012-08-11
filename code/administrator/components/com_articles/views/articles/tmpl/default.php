<?php
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @template('com://admin/default.view.grid.toolbar'); ?>

<module title="" position="sidebar">
    <?= @template('default_sidebar'); ?>
</module>

<module title="" position="inspector">
    <?= @template('com://admin/activities.view.activities.simple', array('package' => 'articles', 'name' => 'article')); ?>
</module>

<form id="articles-form" action="" method="get" class="-koowa-grid">
    <?= @template('default_scopebar'); ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                	 <?= @helper('grid.checkall') ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'title')) ?>
                </th>
                <th width="20">
                    <?= @helper('grid.sort', array('column' => 'state', 'title' => 'Published')) ?>
                </th>
                <th width="10%">
                    <?= @helper('grid.sort', array('column' => 'category_title', 'title' => 'Category')) ?>
                </th>
                <? if($state->category) : ?>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'Order', 'column' => ($state->featured == true) ? 'featured_ordering' : 'ordering')) ?>
                </th>
                <? endif; ?>
                <th width="20%">
                    <?= @helper('grid.sort', array('title' => 'Created', 'column' => 'created')) ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="7">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <? foreach($articles as $article) : ?>
            <tr data-readonly="<?= $article->getStatus() == 'deleted' ? '1' : '0' ?>"  >
                <td align="center">
                    <?= @helper('grid.checkbox' , array('row' => $article)) ?>
                </td>
                <td>
                	<?if($article->getStatus() != 'deleted') : ?>
                        <? if($article->state == -1) : ?>
                		    <?= @escape($article->title).' [ '.@text('Archived').' ] ' ?>
                	    <? else : ?>
                        	<a href="<?= @route('view=article&id='.$article->id) ?>">
                                <?= @escape($article->title) ?>
                        	</a>
                        <? endif ?>
                     <? else : ?>
                     	<?= @escape($article->title); ?>
                     <? endif; ?>
                     <? if($article->featured) : ?>
	                     <span class="label label-success"><?= @text('Featured') ?></span>
                     <? endif; ?>
                     <? if($article->access == '1') : ?>
                         <span class="label label-important"><?= @text('Registered') ?></span>
                     <? elseif($article->access == '2') : ?>
                         <span class="label"><?= @text('Special') ?></span>
                     <? endif; ?>
                </td>
                <td align="center">
                    <?= @helper('grid.state', array('row' => $article, 'option' => 'com_articles', 'view' => 'article')) ?>
                </td>
                <td>
                    <?= $article->category_title ?>
                </td>
                <? if($state->category) : ?>
                <td align="center">
                    <?= @helper('grid.order', array('row' => $article, 'total' => $total)) ?>
                </td>
                <? endif; ?>
                <td>
                    <?= @helper('date.humanize', array('date' => $article->created_on)) ?> by <a href="<?= @route('option=com_users&view=user&id='.$article->created_by) ?>">
                        <?= $article->created_by_name ?>
                    </a>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>