<? /** $Id: default_translators.php 731 2008-09-25 15:55:25Z Johan $ */ ?>
<? defined('_JEXEC') or die('Restricted access'); ?>
<?
$sparkurl   = 'index.php?option=com_nooku&view=statistics.translators&month='.date('m').'&year='.date('Y');
$sparklink  = 'index.php?option=com_nooku&view=statistics&graph=translators';
?>

<h3><?= @text('Translators')?></h3>

<!-- Translators List -->
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th><?= @text('Translator'); ?></th>
        <th><?= @text('Activity'); ?></th>
		<th><?= @text('Enabled'); ?></th>
	</tr>
</thead>
<tbody>
	<? foreach ($this->all_translators as $translator) : ?>
	<tr>
		<td width="35%">
			<?= $translator->name; ?>
		</td>
        <td align="center">
            <? $params = array('user_id' => $translator->user_id, 'h'=>20, 'w'=>100); ?>
            <?= @helper('sparkline.img', $sparkurl, $sparklink, $translator->name, $params);
            ?>
        </td>
		<td width="10%" align="center">
			<?= @helper('grid.boolean', $translator->enabled, null, null, 'Enabled', 'Disabled' ) ?>
		</td>
	</tr>
	<? endforeach; ?>
</tbody>
</table>
