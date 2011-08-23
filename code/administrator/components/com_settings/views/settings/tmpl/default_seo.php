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
	<h3><?= @text( 'SEO' ); ?></h3>
	<table class="admintable" cellspacing="1">
		<tbody>
		<tr>
			<td class="key">
				<span class="editlinktip hasTip" title="<?= @text( 'Search Engine Friendly URLs' ); ?>::<?= @text('Search Engine Optimization Settings'); ?>">
					<?= @text( 'Search Engine Friendly URLs' ); ?>
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
