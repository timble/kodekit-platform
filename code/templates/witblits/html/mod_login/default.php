<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php if($type == 'logout') : ?>
<form action="index.php" method="post" name="form-login" id="login-mod">
	<?php if ($params->get('greeting')) : ?>

	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>

	<?php endif; ?>
	<button class="button logout-btn" type="submit"><?php echo JText::_('BUTTON_LOGOUT'); ?></button>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php else : ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) : ?>
	<?php JHTML::_('script', 'openid'); ?>
<?php endif; ?>
<form action="index.php" method="post" name="form-login" id="login-mod">
	<?php echo $params->get('pretext'); ?>
	<ul class="login-form list-reset">
		<li class="login-username"><label for="username" id="form-login-username"><?php echo JText::_('Username') ?></label>
			<span class="input-wrap"><input name="username" id="username" type="text" class="form-input" alt="username" /></span>
		</li>
		<li class="login-password"><label for="passwd" id="form-login-password"><?php echo JText::_('Password') ?></label>
			<span class="input-wrap"><input type="password" name="passwd" id="passwd" class="form-input" alt="password" /></span>
		</li>
		<li class="login-actions">
			<button class="button login-btn" type="submit"><?php echo JText::_('Log in'); ?></button>
			<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
				<label for="remember" id="form-login-remember">
				    <input type="checkbox" name="remember" id="remember" value="yes" alt="Remember Me" /> 
				    <?php echo JText::_('Remember me'); ?>
				</label>
			<?php endif; ?>
		</li>
	</ul>
	<ul class="login-links">
		<li class="login-forgot"><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
		</li>
		<li class="login-username"><a href="<?php echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?>">
			<?php echo JText::_('FORGOT_YOUR_USERNAME'); ?></a>
		</li>
		<?php $usersConfig = &JComponentHelper::getParams( 'com_users' );
		if ($usersConfig->get('allowUserRegistration')) : ?>
		<li class="login-register"><a href="<?php echo JRoute::_( 'index.php?option=com_user&task=register' ); ?>">
			<?php echo JText::_('REGISTER'); ?></a>
		</li>
		<?php endif; ?>
	</ul>
	<?php echo $params->get('posttext'); ?>
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php endif; ?>