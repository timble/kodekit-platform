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

<form action="" method="post" id="plugin-form" class="-koowa-form">
    <div class="col width-60">
    	<fieldset class="adminform">
        	<legend><?= @text('Details') ?></legend>
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
        				<?= @helper('select.booleanlist', array('name' => 'enabled', 'selected'	=> $plugin->enabled)) ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<label for="folder">
        					<?= @text('Type') ?>:
        				</label>
        			</td>
        			<td>
        				<?= $plugin->type ?>
        			</td>
        		</tr>
        		<tr>
        			<td valign="top" class="key">
        				<label for="element">
        					<?= @text('Plugin file') ?>:
        				</label>
        			</td>
        			<td>
        				<input class="text_area required validate-alphanum" type="text" name="element" id="element" size="35" value="<?= @escape($plugin->name) ?>" />.php
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
				<?= @template('form_accordion', array('params' => $plugin->params, 'id' => 'param-page', 'title' => 'Plugin Parameters')) ?>
	
				<? if($plugin->params->getNumParams('advanced')) : ?>
				<?= @template('form_accordion', array('params' => $plugin->params, 'group' => 'advanced')) ?>
				<? endif ?>
			<?= @helper('accordion.endPane') ?>
    	</fieldset>
    </div>
</form>