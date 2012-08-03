<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Modules
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('behavior.tooltip') ?>
<?= @helper('behavior.validator') ?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" id="plugin-form" class="-koowa-form">
    <div class="form-body">
    	<div class="title">
    		<input class="required" type="text" name="title" value="<?= @escape($plugin->title) ?>" />
    	</div>
    	
    	<div class="form-content">
    	    <fieldset class="form-horizontal">
    	    	<legend><?= @text( 'Details' ); ?></legend>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Type') ?></label>
    			    <div class="controls">
    			        <?= @text($plugin->type) ?>
    			    </div>
    			</div>
    			<div class="control-group">
    			    <label class="control-label"><?= @text('Description') ?></label>
    			    <div class="controls">
    			        <?= @text($plugin->description) ?>
    			    </div>
    			</div>
    		</fieldset>
    		
    		<?= @helper('tabs.startPane') ?>
    			<?= @helper('tabs.startPanel', array('id' => 'default', 'title' => 'Default Parameters')) ?>
    				<?= @template('form_accordion', array('params' => $plugin->params, 'id' => 'param-page', 'title' => 'Plugin Parameters')) ?>
    			<?= @helper('tabs.endPanel') ?>				
    			
    			<? if($plugin->params->getNumParams('advanced')) : ?>
    			<?= @helper('tabs.startPanel', array('id' => 'advanced', 'title' => 'Advanced Parameters')) ?>
    				<?= @template('form_accordion', array('params' => $plugin->params, 'group' => 'advanced')) ?>
    			<?= @helper('tabs.endPanel') ?>
    			<? endif ?>
    		<?= @helper('tabs.endPane') ?>
    	</div>
    </div>
    
    <div class="sidebar">
    	<div class="scrollable">
	    	<fieldset class="form-horizontal">
	        	<legend><?= @text('Details') ?></legend>
	        	<div class="control-group">
	        	    <label class="control-label" for=""><?= @text('Published') ?></label>
	        	    <div class="controls">
	        	        <?= @helper('select.booleanlist', array('name' => 'enabled', 'selected'	=> $plugin->enabled)) ?>
	        	    </div>
	        	</div>
	        	<div class="control-group">
	        	    <label class="control-label" for=""><?= @text('Plugin file') ?></label>
	        	    <div class="controls controls-calendar">
	        	        <input class="required validate-alphanum" type="text" name="element" value="<?= @escape($plugin->name) ?>" />.php
	        	    </div>
	        	</div>
	        	<div class="control-group">
	        	    <label class="control-label" for=""><?= @text('Access Level') ?></label>
	        	    <div class="controls">
	        	        <?= JHTML::_('list.accesslevel', $plugin) ?>
	        	    </div>
	        	</div>
	    	</fieldset>
    	</div>
    </div>
</form>