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
	<h3><?= @text( 'General' ); ?></h3>
	<table class="admintable" cellspacing="1">
	<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Site Name' ); ?>::<?= @text( 'TIPSITENAME' ); ?>">
				<?= @text( 'Site Name' ); ?>
				</span>
			</td>
			<td>
				<input class="text_area" type="text" name="settings[system][sitename]" size="50" value="<?= $settings->sitename; ?>" />
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Require login' ); ?>::<?= @text( 'TIPSETYOURSITEREQUIRESLOGIN' ); ?>">
				<?= @text( 'Require login' ); ?>
				</span>
			</td>
			<td>
			    <?= @helper('select.booleanlist' , array('name' => 'settings[system][offline]', 'selected' => $settings->offline));?>
			</td>
		</tr>
	</tbody>
	</table>
</section>		
		
<section>
	<h3><?= @text( 'Defaults' ); ?></h3>
	<table class="admintable" cellspacing="1">
	<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'WYSIWYG Editor' ); ?>::<?= @text( 'TIPDEFWYSIWYG' ); ?>">
			<?= @text( 'WYSIWYG Editor' ); ?>
			</span>
			</td>
			<td>
				<?= @helper('listbox.editors', array('name' => 'settings[system][editor]', 'selected' => $settings->editor)); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'List Length' ); ?>::<?= @text( 'TIPSETSDEFAULTLENGTHLISTS' ); ?>">
					<?= @text( 'List Length' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.list_limits', array('name' => 'settings[system][list_limit]', 'selected' => $settings->list_limit)); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Feedlimit' ); ?>::<?= @text( 'TIPFEEDLIMIT' ); ?>">
					<?= @text( 'Feed Length' ); ?>
				</span>
			</td>
			<td>
				<?= @helper('listbox.list_limits', array('name' => 'settings[system][feed_limit]', 'selected' => $settings->feed_limit)); ?>
			</td>
		</tr>
	</tbody>
	</table>
</section>
