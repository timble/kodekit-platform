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
	<legend><?php echo JText::_( 'FTP' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_enable]"><?= @text( 'Enable FTP' ); ?></label>
	    <div class="controls">
	        <?php echo @helper('select.booleanlist' , array('name' => 'settings[system][ftp_enable]'));?>
	        <p class="help-block"><?= @text( 'TIPENABLEFTP' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_host]"><?= @text( 'FTP Host' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][ftp_host]" value="<?= $settings->ftp_host; ?>" />
	        <p class="help-block"><?= @text( 'TIPFTPHOST' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_port]"><?= @text( 'FTP Port' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][ftp_port]" value="<?= $settings->ftp_port; ?>" />
	        <p class="help-block"><?= @text( 'TIPFTPPORT' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_user]"><?= @text( 'FTP Username' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][ftp_user]" value="<?= $settings->ftp_user; ?>" />
	        <p class="help-block"><?= @text( 'TIPFTPUSERNAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_pass]"><?= @text( 'FTP Password' ); ?></label>
	    <div class="controls">
	        <input type="password" name="settings[system][ftp_pass]" value="<?= $settings->ftp_pass; ?>" />
	        <p class="help-block"><?= @text( 'TIPFTPPASSWORD' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][ftp_root]"><?= @text( 'FTP Root' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][ftp_root]" value="<?= $settings->ftp_root; ?>" />
	        <p class="help-block"><?= @text( 'TIPFTPROOT' ); ?></p>
	    </div>
	</div>
</fieldset	>
