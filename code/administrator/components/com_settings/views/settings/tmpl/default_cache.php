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
	<legend><?= @text( 'Cache' ); ?></legend>
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
</fieldset>
