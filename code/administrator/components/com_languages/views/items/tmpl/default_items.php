<? foreach($items as $item) : ?>
<tr>
    <td align="center">
        <?= @helper('grid.checkbox', array('row' => $item))?>
    </td>
    <td>
        <?= $item->table ?>
    </td>
    <td align="center">
        <?= $item->row ?>
    </td>
    <td align="center">
        <?= @helper('grid.flag', array('iso_code' => $item->iso_code)) ?>
    </td>
    <td align="left">
        <? if($item->deleted) : ?>
            <?= $item->title ?>
        <? else : ?>
            <a href="<?= @route('view=item&id='.$item->row.'&table='.$item->table.'&lang='.$item->iso_code) ?>">
                <?= @escape($item->title) ?>
            </a>
        <? endif ?>
    </td>
    <td align="center">
        <?= @helper('grid.status', array('status' => $item->status, 'original' => $item->original, 'deleted' => $item->deleted)) ?>
    </td>
    <td>
        <? if($item->created_by) : ?>
            <?= sprintf(@text('%s by %s'), @helper('date.humanize', array('date' => $item->created_on)), $item->created_by_name) ?>
        <? else : ?>
            <?= @text('Never') ?>
        <? endif ?>
    </td>
    <td>
        <? if($item->modified_by) : ?>
            <? sprintf(@text('%s by %s'), @helper('date.humanize', array('date' => $item->modified_on)), $item->modified_by) ?>
        <? else : ?>
            <?= @text('Never') ?>
        <? endif ?>
    </td>
</tr>
<? endforeach ?>