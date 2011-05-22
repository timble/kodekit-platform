<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?>

<script language="javascript" type="text/javascript">
function submitbutton(pressbutton) {
	if (pressbutton == "cancel") {
		submitform(pressbutton);
		return;
	}
	
	var form = document.adminForm;
	if (form.name.value == "") {
		alert( "<?= @text( 'Plugin must have a name', true ); ?>" );
	} else if (form.element.value == "") {
		alert( "<?= @text( 'Plugin must have a filename', true ); ?>" );
	} else {
		submitform(pressbutton);
	}
}
</script>

<form action="<?= @route('id='.$plugin->id) ?>" method="post" class="-koowa-grid">
    <div class="col width-60">
    	<fieldset class="adminform">
        	<legend>
        	    <?= @text('Details') ?>
        	</legend>
        	<table class="admintable">
        		<tr>
        			<td width="100" class="key">
        				<label for="title">
        					<?= @text('Name') ?>:
        				</label>
        			</td>
        			<td>
        				<input class="text_area" type="text" name="title" id="title" size="35" value="<?= @escape($plugin->title) ?>" />
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<?= @text('Published') ?>:
        			</td>
        			<td>
        				<?= @helper('select.booleanlist', array(
        					'name'		=> 'enabled',
        					'selected'	=> $plugin->enabled	
        				)) ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<label for="folder">
        					<?= @text('Type') ?>:
        				</label>
        			</td>
        			<td>
        				<?= $plugin->folder ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<label for="element">
        					<?= @text('Plugin file') ?>:
        				</label>
        			</td>
        			<td>
        				<input class="text_area" type="text" name="element" id="element" size="35" value="<?= @escape($plugin->element) ?>" />.php
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<label for="access">
        					<?= @text('Access Level') ?>:
        				</label>
        			</td>
        			<td>
        				<?= JHTML::_('list.accesslevel', $plugin) ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<?= @text('Order') ?>:
        			</td>
        			<td>
        				<?= @helper('listbox.ordering') ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<?= @text('Description') ?>:
        			</td>
        			<td>
        				<?= @text($plugin->description) ?>
        			</td>
        		</tr>
        		</table>
    	</fieldset>
    </div>
    <div class="col width-40">
    	<fieldset class="adminform">
        	<legend>
        	    <?= @text('Parameters') ?>
        	</legend>
        	<?= @helper('accordion.startPane', array('id' => 'plugin-pane')) ?>
				<?= @template('form_accordion', array('params' => $params, 'id' => 'param-page', 'title' => 'Plugin Parameters')) ?>
	
				<? if($params->getNumParams('advanced')) : ?>
				<?= @template('form_accordion', array('params' => $params, 'group' => 'advanced')) ?>
				<? endif ?>
			<?= @helper('accordion.endPane') ?>
    	</fieldset>
    </div>
</form>