<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<fieldset>
	<legend><?= translate( 'Mail' ); ?></legend>
	<div>
	    <label for=""><?= translate( 'Mailer' ); ?></label>
	    <div>
	        <?= helper('listbox.mailers', array('name' => 'settings[system][mailer]', 'selected' => $settings->mailer)); ?>
	        <p class="help-block"><?= translate( 'TIPMAILER' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'Mail From' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][mailfrom]" value="<?= $settings->mailfrom; ?>" />
	        <p class="help-block"><?= translate( 'TIPMAILFROM' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'From Name' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][fromname]" value="<?= $settings->fromname; ?>" />
	        <p class="help-block"><?= translate( 'TIPFROMNAME' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'Sendmail Path' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][sendmail]" value="<?= $settings->sendmail; ?>" />
	        <p class="help-block"><?= translate( 'TIPSENDMAILPATH' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP Auth' ); ?></label>
	    <div>
	         <?= helper('select.booleanlist' , array('name' => 'settings[system][smtpauth]', 'selected' => $settings->smtpauth));?>
	        <p class="help-block"><?= translate( 'TIPSMTPAUTH' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP Security' ); ?></label>
	    <div>
	        <?= helper('listbox.smtpsecure', array('name' => 'settings[system][smtpsecure]', 'selected' => $settings->smtpsecure)); ?>
	        <p class="help-block"><?= translate( 'TIPSMTPSECURITY' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP Port' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtpport]" value="<?= (isset($settings->smtpport) ? $settings->smtpport : ''); ?>" />
	        <p class="help-block"><?= translate( 'TIPSMTPPORT' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP User' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtpuser]" value="<?= $settings->smtpuser; ?>" />
	        <p class="help-block"><?= translate( 'TIPSMTPUSER' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP Pass' ); ?></label>
	    <div>
	        <input type="password" name="settings[system][smtppass]" value="<?= $settings->smtppass; ?>" />
	        <p class="help-block"><?= translate( 'TIPSMTPPASS' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for=""><?= translate( 'SMTP Host' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][smtphost]" value="<?= $settings->smtphost; ?>" />
	        <p class="help-block"><?= translate( 'TIPSMTPHOST' ); ?></p>
	    </div>
	</div>
</fieldset>
