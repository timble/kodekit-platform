<?= @helper('behavior.keepalive') ?>
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />

<form action="" method="post" id="table-form" class="-koowa-form adminform">
	<fieldset>
		<legend>
			<?= @text( 'Tables list' );?>
		</legend>

		<table>
			<thead>
				<tr>
					<th width="5">
						<?= @text('NUM'); ?>
					</th>
					<th width="20">
						<input type="checkbox" name="toggle" value="" onclick="checkAll(<?= count(@$tables); ?>);" />
					</th>
					<th>
						<?= @text('Table name') ?>
					</th>
					<th>
						<?= @text('Table description') ?>
					</th>
					<th>
						<span class="hasTip" title="<?= @text('Text')?>::<?= @text('Not all tables contain text. in most situations, you\'ll only want to enable translations for tables with text') ?>">
							<?= @text('Text') ?>
						</span>
					</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($tables as $table) : ?>
				<tr class="<?= 'row'.$m; ?>">
					<td align="center">
						<?= $i + 1; ?>
					</td>
					<td align="center">
						<?= @helper('grid.id', $i, $table->table_name); ?>
					</td>
					<td>
						<?= KInflector::humanize($table->table_name); ?>
					</td>
					<td>
						<?= @text($table->comment); ?>
					</td>
					<td>
						<span class="hasTip" title="<?= @text('Text')?>::<?= @text('Not all tables contain text. in most situations, you\'ll only want to enable translations for tables with text') ?>">
							<?= @helper('grid.boolean', $table->has_text ); ?>
						</span>
					</td>	
				</tr>
				<? endforeach; ?>
			</tbody>
		</table>
	
	</fieldset>
</form>