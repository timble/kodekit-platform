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

<fieldset class="adminform">
	<legend><?php echo JText::_( 'FTP' ); ?></legend>
	<table class="admintable" cellspacing="1">

		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Enable FTP' ); ?>::<?= @text( 'TIPENABLEFTP' ); ?>">
						<?= @text( 'Enable FTP' ); ?>
					</span>
			</td>
			<td>
					<?php echo @helper('select.booleanlist' , array('name' => 'settings[system][ftp_enable]'));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'FTP Host' ); ?>::<?= @text( 'TIPFTPHOST' ); ?>">
						<?= @text( 'FTP Host' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][ftp_host]" size="25" value="<?= $settings->ftp_host; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'FTP Port' ); ?>::<?= @text( 'TIPFTPPORT' ); ?>">
						<?= @text( 'FTP Port' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][ftp_port]" size="25" value="<?= $settings->ftp_port; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'FTP Username' ); ?>::<?= @text( 'TIPFTPUSERNAME' ); ?>">
						<?= @text( 'FTP Username' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][ftp_user]" size="25" value="<?= $settings->ftp_user; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'FTP Password' ); ?>::<?= @text( 'TIPFTPPASSWORD' ); ?>">
						<?= @text( 'FTP Password' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="password" name="settings[system][ftp_pass]" size="25" value="<?= $settings->ftp_pass; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'FTP Root' ); ?>::<?= @text( 'TIPFTPROOT' ); ?>">
						<?= @text( 'FTP Root' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][ftp_root]" size="50" value="<?= $settings->ftp_root; ?>" />
			</td>
		</tr>
		</tbody>
	</table>
</fieldset>
