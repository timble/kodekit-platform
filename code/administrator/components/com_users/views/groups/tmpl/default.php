<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.grid.toolbar') ?>

<form action="" method="get" class="-koowa-grid">
    <?= @template('default_scopebar') ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                    <?= @helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'Name')) ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="2">
                    <?= @helper('paginator.pagination', array('total' => $total)) ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <? foreach($groups as $group) : ?>
                <tr>
                    <td align="center">
                        <? if($group->type == 'custom') : ?>
                            <?= @helper('grid.checkbox', array('row' => $group)) ?>
                        <? endif ?>
                    </td>
                    <td>
                        <? if($group->type == 'custom') : ?>
	                        <a href="<?= @route('view=group&id='.$group->id) ?>">
	                            <?= @escape($group->name) ?>
	                        </a>
                        <? else : ?>
                            <?= @escape($group->name) ?>
                        <? endif ?>
                    </td>
                </tr>
            <? endforeach ?>
       </tbody>
    </table>
</form>