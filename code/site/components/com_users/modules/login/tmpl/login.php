<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<form action="<?= @route('option=com_users&view=session'); ?>" method="post" name="login" id="form-login" >

	<?= $pretext ?>
	
	<fieldset class="input">
	<div class="control-group">
		<label class="control-label" for="modlgn_username"><?= @text('Email') ?></label>
		<div class="controls">
			<input type="text" name="email" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="modlgn_passwd"><?= @text('Password') ?></label>
		<div class="controls">
			<input type="password" name="password" />
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= @text('LOGIN') ?>" />
		
		<ul style="margin-top: 14px;">
			<li>
				<a href="<?= @route( 'option=com_users&view=password&layout=token' ); ?>">
				<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
			</li>
			<?php
			
			if ($allow_registration) : ?>
			<li>
				<a href="<?= @route( 'option=com_users&view=user&layout=register' ); ?>">
					<?= @text('REGISTER'); ?></a>
			</li>
			<?php endif; ?>
		</ul>
	</div>
	</fieldset>

	<?= $posttext ?>
</form>