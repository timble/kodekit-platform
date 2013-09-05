<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? foreach ($comments as $comment) : ?>
<tr>
	<td align="center">
		<?= helper('grid.checkbox', array('row' => $comment)); ?>
	</td>
	<td>
		<a href="<?= route('view=comment&table='.$comment->table."&row=".$comment->row); ?>">
			<?= escape($comment->table); ?>
		</a>
	</td>
	<td>
		<a href="<?= route('view=comment&table='.$comment->table."&row=".$comment->row); ?>">
			<?= escape($comment->row); ?>
		</a>
	</td>
	<td>
		<a href="<?= route('view=tag&id='.$comment->id); ?>">
			<?= escape($comment->created_by); ?>
		</a>
	</td>
	<td>
		<a href="<?= route('view=tag&id='.$comment->id); ?>">
			<?= escape($comment->text); ?>
		</a>
	</td>
</tr>
<? endforeach; ?>