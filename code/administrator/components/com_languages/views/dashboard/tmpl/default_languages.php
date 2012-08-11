<? /** $Id: default_languages.php 787 2008-10-28 17:03:08Z mathias $ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>

<?
$sparkurl   = 'index.php?option=com_nooku&view=statistics.translations';
$sparklink  = 'index.php?option=com_nooku&view=statistics&graph=translations';
?>

<h3><?= @text('Languages')?></h3>

<!-- Translatable Languages List -->
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
        <th><?= @text('Language Name'); ?></th>
        <th><?= @text('Flag'); ?></th>
        <th><?= @text('Progress'); ?></th>
        <th><?= @text('ISO Code'); ?></th>
        <th><?= @text('Alias'); ?></th>
		<th><?= @text('Published'); ?></th>
	</tr>
</thead>
<tbody>
	<? foreach (@$all_languages as $language) : ?>
	<tr>
        <td width="35%">
            <?= $language->name; ?>
        </td>
        <td width="31px" align="center">
            <?= @helper('nooku.flag.image', $language); ?>
        </td>
        <td align="center">
            <? $params = array('iso_code' => $language->iso_code, 'h'=>'20'); ?>
            <?= @helper('sparkline.img', $sparkurl, $sparklink, $language->alias, $params); ?>
        </td>
        <td align="center">
            <?= $language->iso_code; ?>
        </td>
        <td width="5%" align="center">
            <?= $language->alias; ?>
        </td>
		<td width="10%" align="center">
			<?= @helper('grid.boolean', $language->enabled, null, null, 'Enabled', 'Disabled' ) ?>
		</td>
	</tr>
	<? endforeach; ?>
</tbody>
</table>
