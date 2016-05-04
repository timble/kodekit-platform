<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="post" class="-koowa-grid">
    <?= import('default_scopebar.html'); ?>
    <table>
        <thead>
            <tr>
                <th width="10">
                    <?= helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'title', 'url' => route())); ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'count', 'url' => route())); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="4">
                    <?= helper('com:theme.paginator.pagination', array('url' => route())) ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <? foreach ($tags as $tag) : ?>
            <tr>
                <td align="center">
                    <?= helper('grid.checkbox', array('entity' => $tag)); ?>
                </td>
                <td>
                    <a href="<?= route('view=tag&id='.$tag->id); ?>">
                        <?= escape($tag->title); ?>
                    </a>
                </td>
                <td>
                    <?= escape($tag->count); ?>
                </td>
            </tr>
            <? endforeach; ?>
            <? if (!count($tags)) : ?>
            <tr>
                <td colspan="4" align="center">
                    <?= translate('No items found'); ?>
                </td>
            </tr>
            <? endif; ?>
        </tbody>
    </table>
</form>