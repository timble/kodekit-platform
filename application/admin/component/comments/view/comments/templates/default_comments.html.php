<?
/**
 * @package     Nooku_Server
 * @subpackage  Comments
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
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