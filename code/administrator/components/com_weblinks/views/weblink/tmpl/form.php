<?
/**
 * @version		$Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<? @helper('behavior.tooltip'); ?>

<script src="media://lib_koowa/js/koowa.js" />

<form action="<?= @route('id='.$weblink->id) ?>" method="post" name="adminForm" id="adminForm">
<input type="hidden" name="id" value="<?= $weblink->id ?>" />

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?= @text( 'Details' ); ?></legend>

		<table class="admintable">
		<tr>
			<td width="100" align="right" class="key">
				<label for="title">
					<?= @text( 'Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="title" id="title" size="32" maxlength="250" value="<?= $weblink->title;?>" />
			</td>
		</tr>
		<tr>
			<td width="100" align="right" class="key">
				<label for="alias">
					<?= @text( 'Alias' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="alias" id="alias" size="32" maxlength="250" value="<?= $weblink->slug;?>" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<?= @text( 'Published' ); ?>:
			</td>
			<td>
				<?= @helper('listbox.enabled', array('state' => $weblink, 'deselect' => false)); ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="catid">
					<?= @text( 'Category' ); ?>:
				</label>
			</td>
			<td>
				<?= @helper('listbox.category', array('selected' => $weblink->catid, 'attribs' => array('id' => 'catid'))) ?>
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="url">
					<?= @text( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="text_area" type="text" name="url" id="url" value="<?= $weblink->url; ?>" size="32" maxlength="250" />
			</td>
		</tr>
		<tr>
			<td valign="top" align="right" class="key">
				<label for="ordering">
					<?= JText::_( 'Ordering' ); ?>:
				</label>
			</td>
			<td>
				<?= @helper('listbox.ordering'); ?>
			</td>
		</tr>
	</table>
	</fieldset>
</div>

<div class="col width-50">
	<fieldset class="adminform">
		<legend><?= @text( 'Description' ); ?></legend>

		<table class="admintable">
		<tr>
			<td>
				<textarea class="text_area" cols="44" rows="9" name="description" id="description"><?= $weblink->description; ?></textarea>
			</td>
		</tr>
		</table>
	</fieldset>
</div>
<div class="clr"></div>
</form>