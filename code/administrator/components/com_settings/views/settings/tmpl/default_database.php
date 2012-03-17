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
	<legend><?= @text( 'Database' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][host]"><?= @text( 'Hostname' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][host]" value="<?= $settings->host; ?>" />
	        <p class="help-block"><?= @text( 'TIPDATABASEHOSTNAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][user]"><?= @text( 'Username' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][user]" value="<?= $settings->user; ?>" />
	        <p class="help-block"><?= @text( 'TIPDATABASEUSERNAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][db]"><?= @text( 'Database' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][db]" value="<?= $settings->db; ?>" />
	        <p class="help-block"><?= @text( 'TIPDATABASENAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][dbprefix]"><?= @text( 'Database Prefix' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][dbprefix]" value="<?= $settings->dbprefix; ?>" />
	        <p class="help-block"><?= @text( 'TIPDATABASEPREFIX' ); ?></p>
	    </div>
	</div>
</fieldset>
