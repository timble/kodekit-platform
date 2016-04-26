<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<table summary="Add Module" class="table">
    <thead>
        <tr>
            <th colspan="2">
                <?= translate('Select module') ?>
            </th>
        </tr>
    </thead>
    <tbody>
    <? $i = 0; foreach($modules as $module) : ?>
        <? if(!$i%2) : ?>
            <tr valign="top">
        <? endif; ?>
        <? $last = $i+1 == count($modules) ?>

        <td width="50%">
            <a href="<?= route('view=module&name='.$module->name.'&application='.parameter('application').'&component='.$module->component) ?>">
                <?= translate(escape($module->name)) ?>
            </a>
        </td>

        <? if($last) : ?>
            <td width="50%">&nbsp;</td>
        <? endif; ?>

        <? if($i%2 || $last) : ?>
            </tr>
        <? endif; ?>
    <? $i++; endforeach ?>
    </tbody>
</table>