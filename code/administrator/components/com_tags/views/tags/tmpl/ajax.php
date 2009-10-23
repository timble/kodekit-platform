<? /** $Id$ */ ?>
<? defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= @route() ?>" method="post" id="tags_tags_form">
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
					<a class="tags_tag_button" rel="<?= http_build_query($tag->getData(), '', '&amp;') ?>" onclick="Tags.delete(this)"><?= @text('Remove') ?></a/>
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