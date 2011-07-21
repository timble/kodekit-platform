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
	<h3><?= @text( 'Database' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td width="185" class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Hostname' ); ?>::<?= @text( 'TIPDATABASEHOSTNAME' ); ?>">
						<?= @text( 'Hostname' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][host]" size="30" value="<?= $settings->host; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Username' ); ?>::<?= @text( 'TIPDATABASEUSERNAME' ); ?>">
						<?= @text( 'Username' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][user]" size="30" value="<?= $settings->user; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Database' ); ?>::<?= @text( 'TIPDATABASENAME' ); ?>">
						<?= @text( 'Database' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][db]" size="30" value="<?= $settings->db; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'Database Prefix' ); ?>::<?= @text( 'TIPDATABASEPREFIX' ); ?>">
						<?= @text( 'Database Prefix' ); ?>
					</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][dbprefix]" size="10" value="<?= $settings->dbprefix; ?>" />
				&nbsp;
				<span class="error hasTip" title="<?= @text( 'Warning' );?>::<?= @text( 'WARNDONOTCHANGEDATABASETABLESPREFIX' ); ?>">
					
				</span>
			</td>
		</tr>
		</tbody>
	</table>
</section>
