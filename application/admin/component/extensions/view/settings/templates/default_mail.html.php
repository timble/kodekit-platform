<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<fieldset>
	<legend><?= @text( 'Mail' ); ?></legend>
	<div>
	    <label for=""><?= @text( 'Mailer' ); ?></label>
	    <div>
	        <?= @helper('listbox.mailers', array('name' => 'settings[system][mailer]', 'selected' => $settings->mailer)); ?>
	        <p class="help-block"><?= @text( 'TIPMAILER' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'Mail From' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][mailfrom]" value="<?= $settings->mailfrom; ?>" />
	        <p class="help-block"><?= @text( 'TIPMAILFROM' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'From Name' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][fromname]" value="<?= $settings->fromname; ?>" />
	        <p class="help-block"><?= @text( 'TIPFROMNAME' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'Sendmail Path' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][sendmail]" value="<?= $settings->sendmail; ?>" />
	        <p class="help-block"><?= @text( 'TIPSENDMAILPATH' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP Auth' ); ?></label>
	    <div>
	         <?= @helper('select.booleanlist' , array('name' => 'settings[system][smtpauth]', 'selected' => $settings->smtpauth));?>
	        <p class="help-block"><?= @text( 'TIPSMTPAUTH' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP Security' ); ?></label>
	    <div>
	        <?= @helper('listbox.smtpsecure', array('name' => 'settings[system][smtpsecure]', 'selected' => $settings->smtpsecure)); ?>
	        <p class="help-block"><?= @text( 'TIPSMTPSECURITY' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP Port' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtpport]" value="<?= (isset($settings->smtpport) ? $settings->smtpport : ''); ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPPORT' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP User' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtpuser]" value="<?= $settings->smtpuser; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPUSER' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP Pass' ); ?></label>
	    <div>
	        <input type="password" name="settings[system][smtppass]" value="<?= $settings->smtppass; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPPASS' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= @text( 'SMTP Host' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtphost]" value="<?= $settings->smtphost; ?>" />
	        <p class="help-block"><?= @text( 'TIPSMTPHOST' ); ?></p>
	    </div>
	</div>
</fieldset>
