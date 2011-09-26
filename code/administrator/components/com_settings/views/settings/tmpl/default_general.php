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
	<h3><?php echo JText::_( 'Path' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'Path to Log-folder' ); ?>::<?= @text( 'TIPLOGFOLDER' ); ?>">
						<?= @text( 'Log-folder' ); ?>
					</span>
				</td>
				<td>
					<input class="text_area" type="text" size="50" name="settings[system][log_path]" value="<?php echo $settings->log_path; ?>" />
				</td>
			</tr>
			<tr>
				<td class="key">
					<span class="editlinktip hasTip" title="<?= @text( 'Path to Temp-folder' ); ?>::<?= @text( 'TIPTMPFOLDER' ); ?>">
						<?= @text( 'Temp-folder' ); ?>
					</span>
				</td>
				<td>
					<input class="text_area" type="text" size="50" name="settings[system][tmp_path]" value="<?= $settings->tmp_path; ?>" />
				</td>
			</tr>
		</tbody>
	</table>
</section>

<section>
	<h3><?= @text( 'Server' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Page Compression' ); ?>::<?= @text( 'Compress buffered output if supported' ); ?>">
					<?= @text( 'Page Compression' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][gzip]'));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text('Force SSL'); ?>::<?= @text( 'TIPFORCESSL' ); ?>">
					<?= @text('Force SSL'); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.force_ssl', array('name' => 'settings[system][force_ssl]', 'selected' => $settings->force_ssl)); ?>
			</td>
		</tr>
		</tbody>
	</table>
</section>

<section>
	<h3><?= @text( 'Debug' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Enable Debugging' ); ?>::<?= @text('TIPDEBUGGINGINFO'); ?>">
					<?= @text( 'Debug System' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][debug]', 'selected' => $settings->debug));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Debug Language' ); ?>::<?= @text('TIPDEBUGLANGUAGE'); ?>">
					<?= @text( 'Debug Language' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][debug_lang]', 'selected' => $settings->debug_lang));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Error Reporting' ); ?>::<?= @text( 'TIPERRORREPORTING' ); ?>">
					<?= @text( 'Error Reporting' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.error_reportings', array('name' => 'settings[system][error_reporting]', 'selected' => $settings->error_reporting)); ?>
			</td>
		</tr>
	</table>
</section>

<section>
	<h3><?= @text( 'Cache' ); ?></h3>
	<table class="admintable" cellspacing="1">

		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Cache' ); ?>::<?= @text( 'TIPCACHE' ); ?>">
					<?= @text( 'Cache' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][caching]', 'selected' => $settings->caching));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Cache Time' ); ?>::<?= @text( 'TIPCACHETIME' ); ?>">
					<?= @text( 'Cache Time' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][cachetime]" size="5" value="<?= $settings->cachetime; ?>" />
					<?= @text( 'minutes' ); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Cache Handler' ); ?>::<?= @text( 'TIPCACHEHANDLER' ); ?>">
					<?= @text( 'Cache Handler' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.cache_handlers', array('name' => 'settings[system][cache_handler]', 'selected' => $settings->cache_handler)); ?>
			</td>
		</tr>
		<? if ($settings->cache_handler == 'memcache' || $settings->session_handler == 'memcache') : ?>
		<tr>
			<td class="key">
				<?= @text( 'Memcache Persistent' ); ?>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][memcache_settings][persistent]', 'selected' => $settings->memcache_settings['persistent']));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?= @text( 'Memcache Compression' ); ?>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][memcache_settings][compression]', 'selected' => $settings->memcache_settings['compression']));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?= @text( 'Memcache Server' ); ?>
			</td>
			<td>
				<?= @text( 'Host' ); ?>:
				<input class="text_area" type="text" name="settings[system][memcache_settings][servers][0][host]" size="25" value="<?= @$settings->memcache_settings['servers'][0]['host']; ?>" />
				<br /><br />
				<?= @text( 'Port' ); ?>:
				<input class="text_area" type="text" name="settings[system][memcache_settings][servers][0][port]" size="6" value="<?= @$settings->memcache_settings['servers'][0]['port']; ?>" />
			</td>
		</tr>
		<? endif; ?>
		</tbody>
	</table>
</section>

<section>
	<h3><?= @text( 'Session' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Session Lifetime' ); ?>::<?= @text( 'TIPAUTOLOGOUTTIMEOF' ); ?>">
					<?= @text( 'Session Lifetime' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][lifetime]" size="10" value="<?= $settings->lifetime; ?>" />
				&nbsp;<?= @text('minutes'); ?>&nbsp;
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Session Handler' ); ?>::<?= @text( 'TIPSESSIONHANDLER' ); ?>">
					<?= @text( 'Session Handler' ); ?>
				</span>
			</td>
			<td>
				<strong><?= @helper('listbox.session_handlers', array('name' => 'settings[system][session_handler]', 'selected' => $settings->session_handler)); ?></strong>
			</td>
		</tr>
		</tbody>
	</table>
</section>

<section>
	<h3><?= @text( 'Locale' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Time Zone' ); ?>::<?= @text( 'TIPDATETIMEDISPLAY' ) .': '. JHTML::_('date',  'now', @text('DATE_FORMAT_LC2')); ?>">
					<?= @text( 'Time Zone' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.offsets', array('name' => 'settings[system][offset]', 'selected' => $settings->offset)); ?>
			</td>
		</tr>
		</tbody>
	</table>
</section>

<section>
	<h3><?= @text( 'Url' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Humanly readable URLs' ); ?>::<?= @text('Humanly readable URLs'); ?>">
					<?= @text( 'Humanly readable URLs' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][sef]', 'selected' => $settings->sef));?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Use mod_rewrite' ); ?>::<?= @text('TIPUSEMODREWRITE'); ?>">
					<?= @text( 'Use mod_rewrite' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][sef_rewrite]', 'selected' => $settings->sef_rewrite));?>
				<span class="error hasTip" title="<?= @text( 'Warning' );?>::<?= @text( 'WARNAPACHEONLY' ); ?>">
					
				</span>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'URL Suffix' ); ?>::<?= @text('TIPURLSUFFIX'); ?>">
					<?= @text( 'Add suffix to URLs' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('select.booleanlist' , array('name' => 'settings[system][sef_suffix]', 'selected' => $settings->sef_suffix));?>
			</td>
		</tr>
		</tbody>
	</table>
</section>