<?
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.tooltip') ?>

<table class="adminlist" summary="Add Module">
	<thead>
		<tr>
			<th colspan="4">
				<?= @text('Select module') ?>
			</th>
		</tr>
	</thead>
	<tbody>
	<? $i = 0; foreach($modules as $module) : ?>
		<? if(!$i%2) : ?>
			<tr valign="top">
		<? endif; ?>
		<? $last = $i+1 == count($modules) ?>

		<td width="50%">
			<span <?= KHelperArray::toString(array(
				'class' 	=> 'editlinktip hasTip',
				'title'		=> @escape(@text($module->description)),
				'name'		=> 'type',
				'value'		=> $module->type
			)) ?>>
				<a href="<?= @route('view=module&layout=form&type='.$module->type.'&application='.$state->application) ?>">
					<?= @text(@escape($module->title)) ?>
				</a>
			</span>
		</td>

		<? if($last) : ?> 
			<td width="50%">&nbsp;</td>
		<? endif; ?>
		
		<? if($i%2 || $last) : ?> 
			</tr>
		<? endif; ?>
	<? $i++; endforeach ?>
	</tbody>
</table>