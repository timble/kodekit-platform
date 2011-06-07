<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Frontpage
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ) ?>

<?= @helper('behavior.tooltip') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route() ?>" method="get" class="-koowa-grid">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="20">
                </th>
                <th class="title">
                    <?= @helper('grid.sort', array('column' => 'title')) ?>
                </th>
                <th width="10%" nowrap="nowrap">
                    <?= @helper('grid.sort', array('column' => 'state', 'title' => 'Published')) ?>
                </th>
                <th width="80" nowrap="nowrap">
                    <?= @helper('grid.sort', array('column' => 'ordering', 'title' => 'Order')) ?>
                    </th>
                <th width="8%" nowrap="nowrap">
                    <?= @helper('grid.sort', array('column' => 'groupname', 'title' => 'Access')) ?>
                </th>
                <th width="10%" class="title">
                    <?= @helper('grid.sort', array('column' => 'section')) ?>
                </th>
                <th width="10%" class="title">
                    <?= @helper('grid.sort', array('column' => 'category')) ?>
                </th>
                <th width="10%" class="title">
                    <?= @helper('grid.sort', array('column' => 'author')) ?>
                </th>

            </tr>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkall') ?>
                </td>
                <td>
                    <?= @helper('grid.search') ?>
                </td>
                <td align="center">
                    <?= @helper('listbox.published', array('name' => 'published')) ?>
                </td>
                <td></td>
                <td>
                    <?= @helper('listbox.access', array('name' => 'access')) ?>
                </td>
                <td>
                    <?= @helper('listbox.sections', array('selected' => $state->section)) ?>
                </td>
                <td>
                    <?= @helper('listbox.categories', array('selected' => $state->category)) ?>
                </td>
                <td>
                    <?= @helper('listbox.authors', array('name' => 'author', 'selected' => $state->author)) ?>
                </td>
            </tr>
        </thead>

 		<tfoot>
            <? if ($articles) : ?>
            <tr>
            	<td colspan="8">
            		<?= @helper('paginator.pagination', array('total' => $total)) ?>
            	</td>
            </tr>
            <? endif ?>
        </tfoot>
      
        <tbody>
            <? foreach($articles as $article) : ?>
                <tr>
                    <td align="center">
                        <?= @helper('grid.checkbox', array('row' => $article)) ?>
                    </td>
                    <td>
                        <a href="<?= @route('option=com_articles&view=article&id='.$article->id) ?>">
                            <?= @escape($article->title) ?>
                        </a>
                    </td>
                    <td align="center">
                        <?= @helper('admin::com.articles.template.helper.grid.state', array('row' => $article)) ?>
                    </td>
                    <td class="order">
                        <?= @helper('grid.order' , array('row' => $article, 'total' => $total)) ?>
                    </td>
                    <td align="center">
                        <?= @helper('grid.access', array('row' => $article, 'url' => 'index.php?option=com_articles&view=article')) ?>
                    </td>
                    <td>
                        <? if($article->section_id) : ?>
                            <a href="<?= @route('option=com_sections&view=section&id='.$article->section_id ) ?>">
                                <?= @escape($article->section_title) ?>
                            </a>
                        <? endif ?>
                    </td>
                    <td>
                        <? if ($article->category_id) : ?>
                            <a href="<?= @route('option=com_categories&view=category&id='.$article->category_id ) ?>">
                                <?= @escape($article->category_title) ?>
                            </a>
                        <? endif ?>
                    </td>
                    <td>
                        <? if(KFactory::get('lib.joomla.user')->authorize('com_users', 'manage')) : ?>
                            <a href="<?= @route('option=com_users&view=user&id='.$article->created_by) ?>">
                                <?= $article->created_by_name ?>
                            </a>
                        <? endif ?>
                    </td>
                </tr>
            <? endforeach ?>
        </tbody>
    </table>
</form>