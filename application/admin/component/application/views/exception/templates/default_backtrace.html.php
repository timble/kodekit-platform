<?
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<table>
    <tr>
        <td colspan="4" align="left" class="TD"><strong><?= @text('Call stack') ?></strong></td>
    </tr>
    <tr>
        <td><strong>#</strong></td>
        <td><strong><?= @text('Function') ?></strong></td>
        <td><strong><?= @text('File') ?></strong></td>
        <td><strong><?= @text('Line') ?></strong></td>
    </tr>
    <? $j = 1; ?>
    <? for( $i = count( $trace ) - 1; $i >= 0 ; $i-- ) : ?>
    <tr>
        <td><?= $j ?></td>
        <? if( isset( $trace[$i]['class'])) : ?>
        <td><?= $trace[$i]['class'].$trace[$i]['type'].$trace[$i]['function'].'()' ?></td>
        <? else : ?>
        <td><?= $trace[$i]['function'].'()' ?></td>
        <? endif; ?>

        <? if( isset( $trace[$i]['file'])) : ?>
        <td><?= $trace[$i]['file'] ?></td>
        <? else : ?>
        <td> </td>
        <? endif; ?>

        <? if( isset( $trace[$i]['line'])) : ?>
        <td><?= $trace[$i]['line']; ?></td>
        <? else : ?>
        <td> </td>
        <? endif; ?>
    </tr>
    <? $j++ ?>
    <? endfor; ?>
</table>