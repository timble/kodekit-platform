<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<? foreach ($terms as $term) : ?>
<tr>
	<td align="center">
		<?= @helper('grid.checkbox', array('row' => $term)); ?>
	</td>
	<td>
		<span class="editlinktip hasTip" title="<?= @text('Edit Term') ?>::<?= @escape($term->title); ?>">
			<a href="<?= @route('view=term&id='.$term->id); ?>">
				<?= @escape($term->title); ?>
			</a>
		</span>
	</td>
	<td>
		<?= @escape($term->slug); ?>
	</td>
	<td>
		<?= @escape($term->count); ?>
	</td>
</tr>
<? endforeach; ?>	