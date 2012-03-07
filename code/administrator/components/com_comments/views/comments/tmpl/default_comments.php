<? foreach ($comments as $comment) : ?>
<tr>
	<td align="center">
		<?= @helper('grid.checkbox', array('row' => $comment)); ?>
	</td>
	<td>
		<a href="<?= @route('view=comment&table='.$comment->table."&row=".$comment->row); ?>">
			<?= @escape($comment->table); ?>
		</a>
	</td>
	<td>
		<a href="<?= @route('view=comment&table='.$comment->table."&row=".$comment->row); ?>">
			<?= @escape($comment->row); ?>
		</a>
	</td>
	<td>
		<a href="<?= @route('view=term&id='.$comment->id); ?>">
			<?= @escape($comment->created_by); ?>
		</a>
	</td>
	<td>
		<a href="<?= @route('view=term&id='.$comment->id); ?>">
			<?= @escape($comment->text); ?>
		</a>
	</td>
</tr>
<? endforeach; ?>