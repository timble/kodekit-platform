<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="" method="get" class="-koowa-grid">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10"></th>
                <th>
                    <?= @helper('grid.sort', array('column' => 'Name')) ?>
                </th>
            </tr>
            <tr>
                <td align="center">
                     <?= @helper( 'grid.checkall') ?>
                </td>
                <td colspan="5">
                    <?= @helper( 'grid.search') ?>
                </td>
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
                        <? if($group->id > 30) : ?>
                            <?= @helper('grid.checkbox', array('row' => $group)) ?>
                        <? endif ?>
                    </td>
                    <td style="padding-left: <?= $group->depth * 15 ?>px">
                        <? if($group->id > 30) : ?>
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