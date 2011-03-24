<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<form action="<?= @route() ?>" method="get" name="adminForm">
    <input type="hidden" name="section" value="<?= $state->section;?>" />
    <input type="hidden" name="type" value="<?= $state->type;?>" />

    <table class="adminlist">
        <thead>
            <tr>
                <th width="20">
					
                </th>
                <th class="title">
                    <?= @helper('grid.sort',  array('column' => 'title')   ); ?>
                </th>
                <th width="5%">
                    <?= @helper('grid.sort',  array('column' => 'published')   ); ?>
                </th>
                <th width="8%" nowrap="nowrap">
                    <?= @text('Ordering') ?>
                </th>
                <th width="7%">
                    <?= @helper('grid.sort',  array('title' => 'Access', 'column' => 'groupname')   ); ?>
                </th>
                <? if ( $state->section == 'com_content') : ?>
                    <th width="20%"  class="title">
                        <?= @helper('grid.sort',  array('title' => 'Section', 'column' => 'section_title')   ); ?>
                    </th>
                    <th width="5%">
                <?= @helper('grid.sort',  array( 'title' => 'Num Active', 'column' => 'activecount') ); ?>
                    </th>
                    <th width="5%" nowrap="nowrap">
                        <?= @helper('grid.sort',  array( 'title' => 'Num Trash', 'column' => 'trashcount') ); ?> 
                    </th>
                <? else : ?>
                     <th width="5%" nowrap="nowrap">
                        <?= @helper('grid.sort',  array( 'title' => 'Num Active', 'column' => 'activecount') ); ?>
                    </th>
                <? endif ?>
            </tr>
            <tr>
                <td align="center">
                    <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows );?>);" />
                </td>
                <td>
                    <?= @template('admin::com.default.view.list.search_form') ?>
                </td>
                <td align="center">
                    <?= @helper('listbox.published', array('name' => 'published', 'attribs' => array('onchange' => 'this.form.submit();'))); ?>
                </td>
                <td></td>
                <td></td>
                <? if ( $state->section == 'com_content') : ?>
                    <td>
                        <?= @helper('admin::com.categories.template.helper.listbox.categories', 
                        array('column'      => 'parent', 
                            'value'         => 'section',
                            'listbox_title' => 'Section',
                            'text'          => 'section_title',
                            'attribs'       => array('onchange' => 'this.form.submit();'),
                           /* 'identifier' => 'admin::com.sections.model.sections', */
                            'filter'        => array('section' => $state->section,
                                                  'distinct' => 'section'))); ?>
                    </td>
                    <td></td>
                    <td></td>
                <? else : ?>
                    <td></td>
                <? endif ?>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="13">
                    <? if ($total) echo @helper('paginator.pagination', array('total' => $total)); ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
        <? if( $total) : ?>
            <? foreach( $categories as $category) :  ?>
                <tr>
                    <td align="center">
                        <?= @helper( 'grid.checkbox' , array('row' => $category)); ?>
                    </td>
                    <td>
                        <span class="editlinktip hasTip" title="<?= @text( 'Title' ).'::'.$category->title; ?>">
                            <a href="<?= @route( 'index.php?&option=com_categories&view=category&id='.$category->id ); ?>"><?= @escape($category->title); ?></a>
                        </span>
                    </td>
                    <td align="center">
                        <?= @helper( 'grid.enable' , array('row' => $category)); ?>
                    </td>
                    <td class="order">
                        <?= @helper( 'grid.order' , array('row' => $category, 'total' => $category->maxorder )); ?>
                    </td>
                    <td align="center">
                        <?= @helper( 'grid.access' , array('row' => $category)); ?>
                    </td>
                    <? if ( $state->section == 'com_content' ) : ?>
                        <td>
                            <a href="<?= @route( 'index.php?&option=com_sections&view=section&id='.$category->section_id )?>" title="<?= @text( 'Edit Section' ); ?>"><?= $category->section_title; ?></a>
                        </td>
                        <td align="center">
                            <?= $category->activecount; ?>
                        </td>
                        <td align="center">
                            <?= $category->trashcount; ?>
                        </td>
                    <? else : ?>
                        <td align="center">
                            <?= $category->activecount; ?>
                        </td>
                    <? endif ?>
                    </tr>
                <? endforeach; ?>
            <? else : ?>
				<tr>
                    <td colspan="10">
                        <?= @text('There are no '.$state->section.' Categories'); ?>
                    </td>
                </tr>
            <? endif ?>
       </tbody>
    </table>
</form>