<?php 
/**
 * @version     $Id: default.php 843 2011-04-06 21:06:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<? if($parameters->def('show_page_title', 1)) : ?>
	<div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
		<?= @escape($parameters->get('page_title')) ?>
	</div>
<? endif ?>

<form action="<?= @route() ?>" method="post" class="josForm form-validate">
	<input type="hidden" name="action" value="remind" />

	<table cellpadding="0" cellspacing="0" border="0" width="100%" class="contentpane">
		<tr>
			<td colspan="2" height="40">
				<p><?= @text('REMIND_USERNAME_DESCRIPTION') ?></p>
			</td>
		</tr>
		<tr>
			<td height="40">
				<label for="email" class="hasTip" title="<?= @text('REMIND_USERNAME_EMAIL_TIP_TITLE') ?>::<?= @text('REMIND_USERNAME_EMAIL_TIP_TEXT') ?>">
				    <?= @text('Email Address') ?>:
				</label>
			</td>
			<td>
				<input id="email" name="email" type="text" class="required validate-email" />
			</td>
		</tr>
	</table>

	<button type="submit" class="validate"><?= @text('Submit') ?></button>
</form>