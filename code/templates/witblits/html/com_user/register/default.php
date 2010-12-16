<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="user-register">
<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>

<?php
	if(isset($this->message)){
		$this->display('message');
	}
?>
<div id="login-wrap">
	<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	<p class="login-description"><?php echo JText::_( 'REGISTER_REQUIRED' ); ?></p>
	<?php endif; ?>

	<form action="<?php echo JRoute::_( 'index.php?option=com_user' ); ?>" method="post" id="josForm" name="josForm" class="form-validate">
	<ul>
		<li class="label"><label id="namemsg" for="name"><?php echo JText::_( 'Name' ); ?> <span class="req">*</span> </label>
	  		<span class="input-wrap"><input type="text" name="name" id="name" size="40" value="<?php echo $this->user->get( 'name' );?>" class="required form-input" maxlength="50" /></span>
	  	</li>
		<li class="label"><label id="usernamemsg" for="username"><?php echo JText::_( 'User name' ); ?> <span class="req">*</span> </label>
			<span class="input-wrap"><input type="text" id="username" name="username" size="40" value="<?php echo $this->user->get( 'username' );?>" class="required validate-username form-input" maxlength="25" /></span>
		</li>
		<li class="label"><label id="emailmsg" for="email"><?php echo JText::_( 'Email' ); ?> <span class="req">*</span> </label>
			<span class="input-wrap"><input type="text" id="email" name="email" size="40" value="<?php echo $this->user->get( 'email' );?>" class="required validate-email form-input" maxlength="100" /></span>
		</li>
		<li class="label"><label id="pwmsg" for="password"><?php echo JText::_( 'Password' ); ?> <span class="req">*</span> </label>
	  		<span class="input-wrap"><input class="required validate-password form-input" type="password" id="password" name="password" size="40" value="" /></span>
	  	</li>
		<li class="label"><label id="pw2msg" for="password2"><?php echo JText::_( 'Verify Password' ); ?> <span class="req">*</span> </label>
			<span class="input-wrap"><input class="required validate-passverify form-input" type="password" id="password2" name="password2" size="40" value="" /></span>
		</li>
		<li class="login-btn">
			<button class="button validate" type="submit"><?php echo JText::_('Register'); ?></button>
		</li>
	</ul>
		<input type="hidden" name="task" value="register_save" />
		<input type="hidden" name="id" value="0" />
		<input type="hidden" name="gid" value="0" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>
</div>