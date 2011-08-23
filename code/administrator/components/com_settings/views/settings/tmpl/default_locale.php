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