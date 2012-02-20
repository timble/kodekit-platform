<? $list = (isset($row) && isset($table)) ? $attachments->find(array('row' => $row, 'table' => $table)) : $attachments ?>

<? if(count($list)) : ?>
<div id="attachments-attachments-list">
    <ul class="attachments">
    <? foreach($list as $item) : ?>
        <li><a href="<?= @route('option=com_attachments&view=attachment&format=file&id='.$item->id) ?>"><?= @escape($item->name) ?></a></li>
    <? endforeach ?>
    </ul>
   </div>
<? endif ?>