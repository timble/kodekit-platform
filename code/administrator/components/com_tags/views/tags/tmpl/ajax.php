<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<form method="post" id="tags_tags_form">
	<table class="adminlist" style="clear: both;">
		<thead>
			<tr>
				<th>
					<?= @text('Tag')?>
				</th>
				<th>
					&nbsp;
				</th>
			</tr>
		</thead>
		<tbody>
			<? $m = 0; ?>
			<? foreach (@$tags as $tag) : ?>
			<tr class="<?php echo 'row'.$m; ?>">
				<td align="center">
					<?= $tag->name; ?>
				</td>
				<td align="center">
					<input type="button" class="tags_tag_button" rel="id=<?= $tag->tags_map_id; ?>&amp;tags_tag_id=<?= $tag->tags_tag_id; ?>&amp;action=deletemapping" value="<?= @text('Remove') ?>" />
				</td>
			</tr>
			<? $m = (1 - $m); ?>
			<? endforeach; ?>

			<tr class="<?php echo 'row'.$m; ?>">
				<td align="center" colspan="2">
					<input name="name" value="" />
				</td>
			</tr>

		</tbody>
	</table>

	<input type="hidden" name="row_id" value="<?= @$state->row_id ?>" />
	<input type="hidden" name="table_name" value="<?= @$state->table_name ?>" />
	<input type="hidden" name="format" value="ajax" />
	<input type="hidden" name="layout" value="ajax" />
	<input type="hidden" name="view" value="tags" />
	<input type="hidden" name="option" value="com_tags" />
	<?// @token(true) ?>
</form>