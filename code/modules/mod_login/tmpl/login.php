<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<form action="<?= JRoute::_('index.php?option=com_users&view=user', true, $usesecure); ?>" method="post" name="login" id="form-login" >
	<input type="hidden" name="action" value="login" />
	
	<?= $pretext ?>
	
	<fieldset class="input">
	<p id="form-login-username">
		<label for="modlgn_username"><?= @text('Username') ?></label><br />
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	</p>
	<p id="form-login-password">
		<label for="modlgn_passwd"><?= @text('Password') ?></label><br />
		<input id="modlgn_passwd" type="password" name="password" class="inputbox" size="18" alt="password" />
	</p>
	<input type="submit" name="Submit" class="button" value="<?= @text('LOGIN') ?>" />
	</fieldset>
	<ul>
		<li>
			<a href="<?= @route( 'option=com_users&view=reset' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?= @route('option=com_users&view=remind' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		
		if ($allow_registration) : ?>
		<li>
			<a href="<?= @route( 'option=com_users&view=user&layout=register' ); ?>">
				<?= @text('REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	
	<?= $posttext ?>
</form>