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
