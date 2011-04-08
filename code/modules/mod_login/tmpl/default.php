<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php if($type == 'logout') : ?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=logout') ?>" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
	<div>
	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>
	</div>
<?php endif; ?>
	<div align="center">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div>

	<input type="hidden" name="action" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<input type="hidden" name="_token" value="<?php echo JUtility::getToken() ?>" />
</form>
<?php else : ?>
<form action="<?php echo JRoute::_('index.php?option=com_users&view=login', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>
	<fieldset class="input">
	<p id="form-login-username">
		<label for="modlgn_username"><?php echo JText::_('Username') ?></label><br />
		<input id="modlgn_username" type="text" name="username" class="inputbox" alt="username" size="18" />
	</p>
	<p id="form-login-password">
		<label for="modlgn_passwd"><?php echo JText::_('Password') ?></label><br />
		<input id="modlgn_passwd" type="password" name="password" class="inputbox" size="18" alt="password" />
	</p>
	<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	</fieldset>
	<ul>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_users&view=reset' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_users&view=remind' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php
		$usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li>
			<a href="<?php echo JRoute::_( 'index.php?option=com_users&view=user&layout=register' ); ?>">
				<?php echo JText::_('REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	<?php echo $params->get('posttext'); ?>

	<input type="hidden" name="action" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<input type="hidden" name="_token" value="<?php echo JUtility::getToken() ?>" />
</form>
<?php endif; ?>