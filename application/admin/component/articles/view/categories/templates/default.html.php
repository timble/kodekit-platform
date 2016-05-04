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
<?= helper('com:theme.behavior.sortable') ?>

<ktml:block prepend="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:block>

<? if(parameter('table') == 'articles') : ?>
    <ktml:block prepend="sidebar">
        <?= import('default_sidebar.html'); ?>
    </ktml:block>
<? endif; ?>

<form action="" method="get" class="-koowa-grid">
    <input type="hidden" name="type" value="<?= parameter('type') ;?>" />

    <?= import('default_scopebar.html'); ?>
    <table>
        <thead>
            <tr>
                <? if(parameter('sort') == 'ordering' && parameter('direction') == 'asc') : ?>
                <th class="handle"></th>
                <? endif ?>
                <th width="1">
                    <?= helper('grid.checkall'); ?>
                </th>
                <th width="1"></th>
                <th>
                    <?= helper('grid.sort',  array('column' => 'title', 'url' => route())); ?>
                </th>
                <th width="1">
                    <?= helper('grid.sort',  array( 'title' => 'Articles', 'column' => 'count', 'url' => route())); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="13">
                    <?= helper('com:theme.paginator.pagination', array('url' => route())); ?>
                </td>
            </tr>
        </tfoot>

        <tbody<? if(parameter('sort') == 'ordering' && parameter('direction') == 'asc') : ?> class="sortable"<? endif ?>>
            <? foreach( $categories as $category) :  ?>
                <tr>
                    <? if(parameter('sort') == 'ordering' && parameter('direction') == 'asc') : ?>
                    <td class="handle">
                        <span class="text--small data-order"><?= $category->ordering ?></span>
                    </td>
                    <? endif ?>
                    <td align="center">
                        <?= helper( 'grid.checkbox' , array('entity' => $category)); ?>
                    </td>
                    <td align="center">
                        <?= helper('grid.enable', array('entity' => $category, 'field' => 'published')) ?>
                    </td>
                    <td>
                        <a href="<?= route( 'view=category&id='.$category->id ); ?>">
                            <?= escape($category->title); ?>
                         </a>
                         <? if($category->access) : ?>
                             <span class="label label-important"><?= translate('Registered') ?></span>
                         <? endif; ?>
                    </td>
                    <td align="center">
                        <?= $category->count; ?>
                    </td>
            	</tr>
            <? endforeach; ?>
       </tbody>
    </table>
</form>
