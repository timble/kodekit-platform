<?
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<script src="media://lib_koowa/js/koowa.js" />
<script src="media://com_articles/js/articles.js" />

<form id="articles-form" action="<?= @route() ?>" method="get" class="-koowa-grid">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10"></th>
                <th>
                    <?= @helper('grid.sort', array('title' => 'Title', 'column' => 'title')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'State', 'column' => 'state')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'Front Page', 'column' => 'frontpage')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'Order', 'column' => 'ordering')) ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort', array('title' => 'Access', 'column' => 'access')) ?>
                </th>
                <th width="8%">
                    <?= @helper('grid.sort', array('title' => 'Section', 'column' => 'section_title')) ?>
                </th>
                <th width="8%">
                    <?= @helper('grid.sort', array('title' => 'Category', 'column' => 'category_title')) ?>
                </th>
                <th width="8%">
                    <?= @helper('grid.sort', array('title' => 'Author', 'column' => 'created_by_name')) ?>
                </th>
                <th width="8%">
                    <?= @helper('grid.sort', array('title' => 'Date', 'column' => 'created')) ?>
                </th>
            </tr>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkall') ?>
                </td>
                <td>
                    <?= @helper('grid.search') ?>
                </td>
                <td>
                    <?= @helper('listbox.states', array('attribs' => array('id' => 'articles-form-state'))) ?>
                </td>
                <td></td>
                <td></td>
                <td>
                    <?= @helper('listbox.access', array('attribs' => array('id' => 'articles-form-access'))) ?>
                </td>
                <td>
                    <?= @helper('listbox.sections', array('selected' => $state->section, 'attribs' => array('id' => 'articles-form-section'))) ?>
                </td>
                <td>
                    <?= @helper('listbox.categories', array('selected' => $state->category, 'attribs' => array('id' => 'articles-form-category'))) ?>
                </td>
                <td>
                    <?= @helper('listbox.authors', array('attribs' => array('id' => 'articles-form-created-by'))) ?>
                </td>
                <td></td>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="10">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        <tbody>
        <? foreach($articles as $article) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox' , array('row' => $article)) ?>
                </td>
                <td>
                	<? if($article->state == -1) : ?>
                		<?= @escape($article->title).' [ '.@text('Archived').' ] ' ?>
                	<? else : ?>
                        <a href="<?= @route('view=article&id='.$article->id) ?>">
                            <?= @escape($article->title) ?>
                        </a>
                    <? endif ?>
                </td>
                <td align="center">
                    <?= @helper('grid.state', array('row' => $article, 'option' => 'com_articles', 'view' => 'article')) ?>
                </td>
                <td align="center">
                    <? // TODO: Make this clickable. ?>
                    <img src="media://lib_koowa/images/<?= $article->frontpage ? 'enabled.png' : 'disabled.png' ?>" />
                </td>
                <td align="center">
                    <?= @helper('grid.order', array('row' => $article, 'total' => $total)) ?>
                </td>
                <td align="center">
                    <?= @helper('grid.access', array('row' => $article)) ?>
                </td>
                <td>
                    <? if($article->section_id) : ?>
                        <a href="<?= @route('option=com_sections&view=section&id='.$article->section_id) ?>">
                            <?= $article->section_title ?>
                        </a>
                    <? else : ?>
                        <?= @text('Uncategorised') ?>
                    <? endif ?>
                </td>
                <td>
                    <? if($article->category_id) : ?>
                        <a href="<?= @route('option=com_categories&view=category&id='.$article->category_id) ?>">
                            <?= $article->category_title ?>
                        </a>
                    <? else : ?>
                        <?= @text('Uncategorised') ?>
                    <? endif ?>
                </td>
                <td>
                    <a href="<?= @route('option=com_users&view=user&id='.$article->created_by) ?>">
                        <?= $article->created_by_name ?>
                    </a>
                </td>
                <td>
                    <?= @helper('date.humanize', array('date' => $article->created_on)) ?>
                </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
</form>