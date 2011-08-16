<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Categories
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?= @route('id='.$category->id) ?>" method="post" class="-koowa-form" id="category-form">
    <div class="grid_8">
        <div class="panel title group">
            <input class="inputbox required" type="text" name="title" id="title" size="40" maxlength="255" value="<?= $category->title ?>" placeholder="<?= @text('Title') ?>" />
            <label for="slug"><?= @text('Slug') ?></label>
            <input class="inputbox" type="text" name="slug" id="slug" size="40" maxlength="255" value="<?= $category->slug ?>" placeholder="<?= @text('Slug') ?>" /> 
        </div>
        <?= @editor(array(
            'name'    => 'description',
            'editor'  => null,
            'width'   => '100%',
            'height'  => '300',
            'cols'    => '60',
            'rows'    => '20',
            'buttons' => true,
            'options' => array('theme' => 'simple', 'pagebreak', 'readmore')
        ));
        ?>
    </div>

    <div class="grid_4">
        <div class="panel">
            <h3><?= @text('Publish') ?></h3>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <?= @text('Published') ?>:
                    </td>
                    <td>
                        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected' => $category->enabled)) ?>
                    </td>
                </tr>
                <? $section = $category->id ? $category->section : $state->section ?>
                <? if(substr($section, 0, 3) != 'com' || $section =='com_content') : ?>
                    <tr>
                        <td class="key">
                            <label for="section"><?= @text('Section') ?>:</label>
                        </td>
                        <td>
                            <input type="hidden" name="old_parent" value="<?= $category->section ?>" />
                            <?= @helper('listbox.sections', array('name' => 'section', 'selected' => $category->section, 'attribs' => array('id' => 'section', 'class' => 'required'))) ?>
                        </td>
                    </tr>
                <? else : ?>
                    <input type="hidden" name="section" value="<?= $section ?>" />
                <? endif ?>
                <tr>
                    <td valign="top" class="key">
                        <label for="access"> <?= @text('Access Level') ?>:</label>
                    </td>
                    <td>
                        <?= @helper('listbox.access', array('name' => 'access', 'state' => $category, 'deselect' => false)) ?>
                    </td>
                </tr>
            </table>
        </div>

        <div class="panel">
            <h3>
                <?= @text('Image') ?>
            </h3>
            <table class="admintable">
                <tr>
                    <td class="key">
                        <label for="image"> <?= @text('Image') ?>:</label>
                    </td>
                    <td><?= @helper('image.listbox', array('name' => 'image')) ?>
                    </td>
                </tr>
                <tr>
                    <td nowrap="nowrap" class="key">
                        <label for="image_position"><?= @text('Image Position') ?>:</label>
                    </td>
                    <td>
                        <?=  @helper('image.position') ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</form>