<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Server' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][gzip]"><?= @text( 'Page Compression' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][gzip]', 'selected' => $settings->gzip));?>
	        <p class="help-block"><?= @text( 'Compress buffered output if supported' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Diognostics' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][debug]"><?= @text( 'Application Profiling' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][debug]', 'selected' => $settings->debug));?>
	        <p class="help-block"><?= @text('TIPDEBUGGINGINFO'); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][debug_lang]"><?= @text( 'Language Indicators' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][debug_lang]', 'selected' => $settings->debug_lang));?>
	        <p class="help-block"><?= @text('TIPDEBUGLANGUAGE'); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Errors' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][error_reporting]"><?= @text( 'Error Reporting' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.error_reportings', array('name' => 'settings[system][error_reporting]', 'selected' => $settings->error_reporting)); ?>
	        <p class="help-block"><?= @text( 'TIPERRORREPORTING' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Cache' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][caching]"><?= @text( 'Cache' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][caching]', 'selected' => $settings->caching));?>
	        <p class="help-block"><?= @text( 'TIPCACHE' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][cachetime]"><?= @text( 'Cache Time' ); ?></label>
	    <div class="controls">
	        <div class="input-append input-normal">
	            <input type="text" name="settings[system][cachetime]" value="<?= $settings->cachetime; ?>" />
	            <span class="add-on"><?= @text( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= @text( 'TIPCACHETIME' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Session' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Session Lifetime' ); ?></label>
	    <div class="controls">
	        <div class="input-append input-normal">
	            <input type="text" name="settings[system][lifetime]" value="<?= $settings->lifetime; ?>" />
	            <span class="add-on"><?= @text( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= @text( 'TIPAUTOLOGOUTTIMEOF' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Locale' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Time Zone' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.timezones', array('name' => 'settings[system][timezone]', 'selected' => $settings->timezone)) ?>
	        <p class="help-block"><?= @text( 'TIPDATETIMEDISPLAY' ) .': '. @helper('date.format', array('format' => @text('DATE_FORMAT_LC2'))) ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Url' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Use mod_rewrite' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][sef_rewrite]', 'selected' => $settings->sef_rewrite));?>
	        <p class="help-block"><?= @text('TIPUSEMODREWRITE'); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Add suffix to URLs' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][sef_suffix]', 'selected' => $settings->sef_suffix));?>
	        <p class="help-block"><?= @text('TIPURLSUFFIX'); ?></p>
	    </div>
	</div>
</fieldset>