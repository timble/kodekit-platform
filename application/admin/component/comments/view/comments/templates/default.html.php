<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="get" class="-koowa-grid">
    <table>
        <thead>
            <tr>
                <th width="1">
                    <?= helper('grid.checkall') ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'created_on', 'title' => 'Date')); ?>
                </th>
                <th>
                    <?= helper('grid.sort', array('column' => 'text')); ?>
                </th>
            </tr>
        </thead>
        <tfoot>
        <tr>
            <td colspan="20">
                <?= helper('com:application.paginator.pagination', array('total' => $total)) ?>
            </td>
        </tr>
        </tfoot>
        <tbody>
        <? foreach ($comments as $comment) : ?>
            <tr>
                <td align="center">
                    <?= helper('grid.checkbox', array('row' => $comment)); ?>
                </td>
                <td>
                    <?= helper('date.humanize', array('date' => $comment->created_on)) ?> by
                    <a href="<?= route('option=com_users&view=user&id='.$comment->created_by) ?>">
                        <?= escape($comment->created_by_name); ?>
                    </a> on
                    <a href="<?= route('view=comment&table='.$comment->table."&row=".$comment->row); ?>">
                        <?= escape($comment->title); ?>
                    </a>
                </td>
                <td style="width: 5%" class="class="ellipsis"">
                    <a href="<?= route('view=comment&id='.$comment->id); ?>">
                        <?= escape(strip_tags($comment->text)); ?>
                    </a>
                </td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>
</form>