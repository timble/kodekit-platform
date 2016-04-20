<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<form action="" method="get" class="-koowa-grid">
    <?= import('default_scopebar.html') ?>
    <table>
        <thead>
            <tr>
                <th width="1">
                    <?= helper('grid.checkall'); ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'Name')) ?>
                </th>
                <th>
                    <?= translate('Description') ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="3">
                    <?= helper('com:theme.paginator.pagination') ?>
                </td>
            </tr>
        </tfoot>

        <tbody>
            <? foreach($groups as $group) : ?>
                <tr>
                    <td align="center">
                        <?= helper('grid.checkbox', array('entity' => $group)) ?>
                    </td>
                    <td>
                        <a href="<?= route('view=group&id='.$group->id) ?>">
                            <?= escape($group->name) ?>
                        </a>
                    </td>
                    <td>
                        <?= escape($group->description) ?>
                    </td>
                </tr>
            <? endforeach ?>
       </tbody>
    </table>
</form>
