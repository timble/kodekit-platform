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
<?= @helper('behavior.validator') ?>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<script>
window.addEvent('domready', function(){
    $('plugin-form').addEvent('validate', function(){
        var errors = [];

        if(!$('title').get('value').trim()) {
            errors.include(<?= json_encode(@text('Plugin must have a title.')) ?>);
        }

        if(!$('element').get('value').trim()) {
        	errors.include(<?= json_encode(@text('Plugin must have a filename.')) ?>);
        }

        if(errors.length) {
            alert(errors.join('\n'));
            return false;
        }
    });
});
</script>

<form action="<?= @route('id='.$plugin->id) ?>" method="post" id="plugin-form" class="-koowa-form">
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
        				<input class="text_area required" type="text" name="title" id="title" size="35" value="<?= @escape($plugin->title) ?>" />
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
        				<input class="text_area required validate-alphanum" type="text" name="element" id="element" size="35" value="<?= @escape($plugin->element) ?>" />.php
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