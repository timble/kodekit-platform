<? /** $Id: default_changes.php 972 2009-04-12 22:42:27Z johan $ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<h3><?= @text('Latest Translated Items')?></h3>

<? if(!count(@$changes)) { echo @text('No items found'); return; }?>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th><?= @text('Title'); ?></th>
		<th><?= @text('ISO'); ?></th>
        <th><?= @text('Modified by'); ?></th>
        <th><?= @text('Modified'); ?></th>
        <th><?= @text('Edit'); ?></th>
	</tr>
</thead>
<tbody>
	<? $k = 0; $i = 0;
    foreach(@$changes as $row) :
    $k = 1-$k;?>
	<tr class="<?= 'row'.$k; ?>">
		<td width="40%">
            <?= @helper('nooku.node.link', $row->title, $row->table_name, $row->row_id, $row->iso_code);?>
		</td>
		<td width="31" align="center">
            <?= @helper('nooku.flag.image', @$all_languages[$row->iso_code]);?>
		</td>
        <td align="center">
            <?= $row->modified_by_name?>
        </td>
        <td align="center">
            <?= $row->modified;?>
        </td>
        <td>
            <? foreach(@$all_languages as $lang) :?>
                <? $flag = @helper('nooku.flag.image', $lang);?>
                <?= @helper('nooku.node.link', $flag, $row->table_name, $row->row_id, $lang->iso_code, false, false);?>
            <? endforeach; ?>
        </td>
	</tr>
	<? endforeach; ?>
</tbody>
</table>
