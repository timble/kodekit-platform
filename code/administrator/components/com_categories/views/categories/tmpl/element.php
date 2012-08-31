<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
    defined('KOOWA') or die('Restricted access') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('tmpl=component') ?>" method="get" class="-koowa-grid">
    <table class="adminlist" cellspacing="1">
        <thead>
        <tr>
            <th class="title">
                <?= @helper('grid.sort', array('title' => 'Title', 'column' => 'title')) ?>
            </th>
            <th>
                <?=@helper('grid.sort', array('title' => @text('Section'), 'column' => 'section_id'));?>
            </th>
            <th width="7%">
                <?= @helper('grid.sort', array('title' => 'Access', 'column' => 'access')) ?>
            </th>
        </tr>
        <tr>
            <td>
                <?= @helper('grid.search') ?>
            </td>
            <td colspan="2"></td>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="3">
                <?= @helper('paginator.pagination', array('total' => $total)) ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <? foreach($categories as $category) : ?>
        <tr>
            <td>
                <a style="cursor: pointer;" onclick="window.parent.jSelectCategory('<?= $category->id ?>', '<?= str_replace(array("'", "\""), array("\\'", ""), $category->title); ?>', '<?php echo JRequest::getVar('object'); ?>');">
                    <?= @escape($category->title) ?>
                </a>
            </td>
            <td align="center">
                <?=$state->section == 'com_content'?@escape($category->section_title):@escape($category->section_id);?>
            </td>
            <td align="center">
                <?= $category->access; ?>
            </td>
        </tr>
            <? endforeach ?>
        </tbody>
    </table>
</form>