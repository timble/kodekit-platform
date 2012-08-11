<? /** $Id: default_tables.php 731 2008-09-25 15:55:25Z Johan $ */ ?>
<?php defined('_JEXEC') or die('Restricted access'); ?>

<h3><?= @text('Tables')?></h3>

<!-- Translatable Database Tables List -->
<table class="adminlist" cellspacing="1">
<thead>
	<tr>
		<th><?= @text('Table Name'); ?></th>
		<th><?= @text('Enabled'); ?></th>
	</tr>
</thead>
<tbody>
	<? foreach (@$all_tables as $table) : ?>
	<tr>
		<td width="90%">
			<?= KInflector::humanize($table->table_name); ?>
		</td>
		<td width="10%" align="center">
			<?= @helper('grid.boolean', $table->enabled, null, null, 'Enabled', 'Disabled' ) ?>
		</td>
	</tr>
	<? endforeach; ?>
</tbody>
</table>
