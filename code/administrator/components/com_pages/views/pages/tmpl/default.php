<?
/**
 * @version     $Id: default.php 3216 2011-11-28 15:33:44Z kotuha $
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar') ?>

<ktml:module position="sidebar">
    <?= @template('default_sidebar') ?>
</ktml:module>

<ktml:module position="inspector">
    <?= @template('com://admin/activities.view.activities.simple', array('package' => 'pages', 'name' => 'page')); ?>
</ktml:module>

<form id="pages-form" action="" method="get" class="-koowa-grid" >
    <?= @template('default_scopebar') ?>
    <table>
        <thead>
            <tr>
                <th width="5"></th>
                <th width="60%">
                    <?= @helper('grid.sort', array('column' => 'title')); ?>
                </th>
                <th width="5">
                    <?= @helper('grid.sort', array('column' => 'enabled' , 'title' => 'Published')); ?>
                </th>
                <th width="80">
                    <?= @helper('grid.sort',  array('column' => 'custom' , 'title' => 'Ordering')); ?>
                </th>
                <th>
                    <?= @helper('grid.sort',  array('column' => 'component_id' , 'title' => 'Type')); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td colspan="6">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <? foreach($pages as $page) : ?>
            <tr class="sortable">
                <td align="center">
                    <?= @helper('grid.checkbox',array('row' => $page)); ?>
                </td>
                <td>
                    <?
                        $link = 'type[name]='.$page->type;
                        if($page->type == 'component') {
                            $link .= '&type[option]='.$page->link->query['option'].'&type[view]='.$page->link->query['view'];
                            $link .= '&type[layout]='.(isset($page->link->query['layout']) ? $page->link->query['layout'] : 'default');
                        }

                        $link .= '&view=page&menu='.$state->menu.'&id='.$page->id;
                    ?>
                    <a href="<?= urldecode(@route($link)) ?>">
                        <? if($page->level > 1) : ?>
                            <?= str_repeat('.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', $page->level - 1) ?><sup>|_</sup>&nbsp;
                        <? endif ?>
                        <?= @escape($page->title) ?>
                    </a>
                    <? if($page->home) : ?>
                        <img src="media://system/images/star.png" alt="<?= @text('Default') ?>" />
                    <? endif ?>
                    <? if($page->access == '1') : ?>
                        <span class="label label-important"><?= @text('Registered') ?></span>
                    <? elseif($page->access == '2') : ?>
                        <span class="label"><?= @text('Special') ?></span>
                    <? endif; ?>
                </td>
                <td align="center">
                    <?= @helper('grid.enable', array('row' => $page)) ?>
                </td>
                <td align="center">
                    <?= @helper('grid.order', array('row'=> $page, 'total' => $total)) ?>
                </td>
                <td>
                    <?= $page->type_description ?>
                </td>
            </tr>
            <? endforeach; ?>
        </tbody>
    </table>
</form>
