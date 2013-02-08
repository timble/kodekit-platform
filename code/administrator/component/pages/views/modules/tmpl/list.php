<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Pages
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<table summary="Add Module" class="table">
	<thead>
		<tr>
			<th colspan="2">
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
			<span class="editlinktip hasTip" title="<?= @escape(@text($module->description)) ?>">
				<a href="<?= @route('view=module&layout=form&name='.$module->name.'&application='.$state->application.'&component='.$module->extensions_component_id) ?>">
					<?= @text(@escape($module->name)) ?>
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