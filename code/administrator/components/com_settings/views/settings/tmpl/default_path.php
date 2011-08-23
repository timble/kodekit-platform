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