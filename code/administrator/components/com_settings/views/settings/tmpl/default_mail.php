<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<section>
	<h3><?= @text( 'Mail' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Mailer' ); ?>::<?= @text( 'TIPMAILER' ); ?>">
					<?= @text( 'Mailer' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.mailers', array('name' => 'settings[system][mailer]', 'selected' => $settings->mailer)); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Mail From' ); ?>::<?= @text( 'TIPMAILFROM' ); ?>">
					<?= @text( 'Mail From' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][mailfrom]" size="30" value="<?= $settings->mailfrom; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'From Name' ); ?>::<?= @text( 'TIPFROMNAME' ); ?>">
					<?= @text( 'From Name' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][fromname]" size="30" value="<?= $settings->fromname; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Sendmail Path' ); ?>::<?= @text( 'TIPSENDMAILPATH' ); ?>">
					<?= @text( 'Sendmail Path' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][sendmail]" size="30" value="<?= $settings->sendmail; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'SMTP Auth' ); ?>::<?= @text( 'TIPSMTPAUTH' ); ?>">
					<?= @text( 'SMTP Auth' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][smtpauth]'));?>
			</td>
		</tr>
        <tr>
 			<td class="key">
   				<span class="editlinktip hasTip" title="<?= @text( 'SMTP Security' ); ?>::<?= @text( 'TIPSMTPSECURITY' ); ?>">
	    			<?= @text( 'SMTP Security' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.smtpsecure', array('name' => 'settings[system][smtpsecure]', 'selected' => $settings->smtpsecure)); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'SMTP Port' ); ?>::<?= @text( 'TIPSMTPPORT' ); ?>">
					<?= @text( 'SMTP Port' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][smtpport]" size="30" value="<?= (isset($settings->smtpport) ? $settings->smtpport : ''); ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'SMTP User' ); ?>::<?= @text( 'TIPSMTPUSER' ); ?>">
					<?= @text( 'SMTP User' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][smtpuser]" size="30" value="<?= $settings->smtpuser; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'SMTP Pass' ); ?>::<?= @text( 'TIPSMTPPASS' ); ?>">
					<?= @text( 'SMTP Pass' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="password" name="settings[system][smtppass]" size="30" value="<?= $settings->smtppass; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'SMTP Host' ); ?>::<?= @text( 'TIPSMTPHOST' ); ?>">
					<?= @text( 'SMTP Host' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][smtphost]" size="30" value="<?= $settings->smtphost; ?>" />
			</td>
		</tr>
		</tbody>
	</table>
</section>
