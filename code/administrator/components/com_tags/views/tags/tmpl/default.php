<? /** $Id: default.php 299 2009-10-24 00:19:50Z johan $ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<? @script(@$mediaurl.'/com_tags/js/tags.js') ?>

<div id="tags-panel">
	<form action="<?= @route(); ?>" method="post" id="tags-form">
		<input type="hidden" name="row_id"     value="<?= @$state->row_id?>" />
		<input type="hidden" name="table_name" value="<?= @$state->table_name?>" />
		<table class="adminlist" style="clear: boyth;">
		<thead>
			<tr>
				<th><?= @text('Tag')?></th>
				<th>&nbsp;</th>
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
					<a class="tags-button" rel="<?= http_build_query($tag->getData(), '', '&amp;') ?>" onclick="Tags.execute('delete', this.rel)"><?= @text('Remove') ?></a/>
				</td>
			</tr>
			<? $m = (1 - $m); ?>
			<? endforeach; ?>

			<tr class="<?php echo 'row'.$m; ?>">
				<td align="center" colspan="2">
					<input name="name" value="" style="width: 80%"/>
				</td>
			</tr>
		</tbody>
	</table>
</form>
</div>