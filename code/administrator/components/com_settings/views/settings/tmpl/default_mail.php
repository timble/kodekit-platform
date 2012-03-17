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
	<legend><?= @text( 'Mail' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Mailer' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.mailers', array('name' => 'settings[system][mailer]', 'selected' => $settings->mailer)); ?>
	        <p class="help-block"><?= @text( 'TIPMAILER' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Mail From' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][mailfrom]" value="<?= $settings->mailfrom; ?>" />
	        <p class="help-block"><?= @text( 'TIPMAILFROM' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'From Name' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][fromname]" value="<?= $settings->fromname; ?>" />
	        <p class="help-block"><?= @text( 'TIPFROMNAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'Sendmail Path' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][sendmail]" value="<?= $settings->sendmail; ?>" />
	        <p class="help-block"><?= @text( 'TIPSENDMAILPATH' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP Auth' ); ?></label>
	    <div class="controls">
	         <div class="controls-radio"><?= @helper('select.booleanlist' , array('name' => 'settings[system][smtpauth]', 'selected' => $settings->smtpauth));?></div>
	        <p class="help-block"><?= @text( 'TIPSMTPAUTH' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP Security' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.smtpsecure', array('name' => 'settings[system][smtpsecure]', 'selected' => $settings->smtpsecure)); ?>
	        <p class="help-block"><?= @text( 'TIPSMTPSECURITY' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP Port' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][smtpport]" value="<?= (isset($settings->smtpport) ? $settings->smtpport : ''); ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPPORT' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP User' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][smtpuser]" value="<?= $settings->smtpuser; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPUSER' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP Pass' ); ?></label>
	    <div class="controls">
	        <input type="password" name="settings[system][smtppass]" value="<?= $settings->smtppass; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPPASS' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for=""><?= @text( 'SMTP Host' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][smtphost]" value="<?= $settings->smtphost; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPHOST' ); ?></p>
	    </div>
	</div>
</fieldset>
