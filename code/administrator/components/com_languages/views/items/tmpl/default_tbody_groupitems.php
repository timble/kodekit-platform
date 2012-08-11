<? /** $Id:default_tbody_table_name.php 222 2007-12-13 21:48:32Z mjaz $ */ ?>
<? defined('_JEXEC') or die; ?>

<? $i = 0; $m = 0;
foreach (@$items as $item) :
    $m = $item->original ? (1 - $m) : $m;
?>
<tr class="<?= 'row'.$m; ?>">
    <td align="center">
        <?= $i + 1; ?>
    </td>
    <td align="center">
        <?= @helper('grid.id', $i, $item->nooku_node_id, $item->deleted); ?>
    </td>
    <? if($item->original) : ?>
        <td align="center">
            <?= $item->table_name?>
        </td>
        <td align="center">
            <?= $item->row_id?>
        </td>
    <? else : ?>
        <td colspan="2">&nbsp;</td>
    <? endif; ?>
    <td align="center">
        <?= @helper('nooku.flag.image', $item);?>
    </td>
    <td align="left">
        <?= $item->deleted ? $item->title : @helper('nooku.node.link', $item->title, $item->table_name, $item->row_id, $item->iso_code);?>
    </td>
    <td align="center">
        <?= @helper('nooku.string.status', $item->status, $item->original, $item->deleted);?>
    </td>
    <td align="center">
        <?= $item->created_by_name?>
    </td>
    <td align="center">
        <?= $item->modified_by_name?>
    </td>
    <td align="center">
        <?= $item->created?>
    </td>
    <td align="center">
        <?= $item->modified?>
    </td>
</tr>
<? $i = $i + 1;?>
<? endforeach; ?>