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
	<legend><?php echo JText::_( 'Path' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][log_path]"><?= @text('Log-folder'); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][log_path]" value="<?php echo $settings->log_path; ?>" />
	        <p class="help-block"><?= @text( 'TIPLOGFOLDER' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][tmp_path]"><?= @text( 'Temp-folder' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][tmp_path]" value="<?= $settings->tmp_path; ?>" />
	        <p class="help-block"><?= @text( 'TIPTMPFOLDER' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset class="form-horizontal">
	<legend><?= @text( 'Server' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][gzip]"><?= @text( 'Page Compression' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][gzip]', 'selected' => $settings->gzip));?>
	        <p class="help-block"><?= @text( 'Compress buffered output if supported' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][force_ssl]"><?= @text('Force SSL'); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.force_ssl', array('name' => 'settings[system][force_ssl]', 'selected' => $settings->force_ssl)); ?>
	        <p class="help-block"><?= @text( 'TIPFORCESSL' ); ?></p>
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
	<div class="control-group">
	    <label class="control-label" for="settings[system][cache_handler]"><?= @text( 'Cache Handler' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.cache_handlers', array('name' => 'settings[system][cache_handler]', 'selected' => $settings->cache_handler)); ?>
	        <p class="help-block"><?= @text( 'TIPCACHEHANDLER' ); ?></p>
	    </div>
	</div>
	<? if ($settings->cache_handler == 'memcache' || $settings->session_handler == 'memcache') : ?>
	<div class="control-group">
	    <label class="control-label" for="settings[system][memcache_settings][persistent]"><?= @text( 'Memcache Persistent' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][memcache_settings][persistent]', 'selected' => $settings->memcache_settings['persistent']));?>
	        <p class="help-block"></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][memcache_settings][compression]"><?= @text( 'Memcache Compression' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][memcache_settings][compression]', 'selected' => $settings->memcache_settings['compression']));?>
	        <p class="help-block"></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][memcache_settings][servers][0][host]"><?= @text( 'Memcache Server - Host' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][memcache_settings][servers][0][host]" value="<?= @$settings->memcache_settings['servers'][0]['host']; ?>" />
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][memcache_settings][servers][0][port]"><?= @text( 'Memcache Server - Port' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][memcache_settings][servers][0][port]" value="<?= @$settings->memcache_settings['servers'][0]['port']; ?>" />
	    </div>
	</div>
	<? endif; ?>
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
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Session Handler' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.session_handlers', array('name' => 'settings[system][session_handler]', 'selected' => $settings->session_handler)); ?>
	        <p class="help-block"><?= @text( 'TIPSESSIONHANDLER' ); ?></p>
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
	    <label class="control-label" for=""><?= @text( 'Humanly readable URLs' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][sef]', 'selected' => $settings->sef));?>
	        <p class="help-block"><?= @text('Humanly readable URLs'); ?></p>
	    </div>
	</div>
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