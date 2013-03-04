<?php
/**
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Terms
 * @copyright   Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @template('com://admin/default.view.grid.toolbar.html'); ?>

<form action="" method="post" class="-koowa-grid">
    <?= @template('default_scopebar.html'); ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                    <?= @helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'title')); ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'count')); ?>
                </th>
            </tr>
        </thead>
        
        <tfoot>
            <tr>
                <td colspan="4">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>
        
        <tbody>        
            <? foreach ($terms as $term) : ?>
            <tr>
                <td align="center">
                    <?= @helper('grid.checkbox', array('row' => $term)); ?>
                </td>
                <td>
                    <span class="editlinktip hasTip" title="<?= @text('Edit Term') ?>::<?= @escape($term->title); ?>">
                        <a href="<?= @route('view=term&id='.$term->id); ?>">
                            <?= @escape($term->title); ?>
                        </a>
                    </span>
                </td>
                <td>
                    <?= @escape($term->count); ?>
                </td>
            </tr>
            <? endforeach; ?>	
            <? if (!count($terms)) : ?>
            <tr>
                <td colspan="4" align="center">
                    <?= @text('No items found'); ?>
                </td>
            </tr>
            <? endif; ?>
        </tbody>
    </table>
</form>