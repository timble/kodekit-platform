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
