<? /** $Id: default_deletes.php 795 2008-10-31 01:08:15Z mathias $ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<h3><?= @text('Latest Deleted Items')?></h3>

<? if(!count(@$deletes)) { echo @text('No items found'); return; }?>

<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th><?= @text('Title'); ?></th>
		<th><?= @text('ISO'); ?></th>
        <th><?= @text('Deleted by'); ?></th>
        <th><?= @text('Deleted'); ?></th>
	</tr>
</thead>
<tbody>
	<? $k = 0; $i = 0;
    foreach(@$deletes as $row) :
    $k = 1-$k;?>
	<tr class="<?= 'row'.$k; ?>">
		<td width="40%">
            <?= $row->title;?>
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
	</tr>
	<? endforeach; ?>
</tbody>
</table>
