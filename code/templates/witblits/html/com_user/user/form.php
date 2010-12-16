<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<script type="text/javascript">
<!--
	Window.onDomReady(function(){
		document.formvalidator.setHandler('passverify', function (value) { return ($('password').value == value); }	);
	});
// -->
</script>

<div id="edit-user">
<form action="<?php echo JRoute::_( 'index.php' ); ?>" method="post" name="userform" autocomplete="off" class="form-validate">
<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
	<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
<?php endif; ?>
<ul class="list-reset form">
	<li><label for="username" class="label"><?php echo JText::_( 'User Name: ' ); ?></label>
	<?php echo $this->user->get('username');?></li>
	
	<li><label for="name" class="label"><?php echo JText::_( 'Your Name: ' ); ?></label>
	<input class="text-input required" type="text" id="name" name="name" value="<?php echo $this->user->get('name');?>" size="40" /></li>
	
	<li><label for="email" class="label"><?php echo JText::_( 'Email' ); ?></label>
	<input class="text-input required validate-email" type="text" id="email" name="email" value="<?php echo $this->user->get('email');?>" size="40" /></li>

	<?php if($this->user->get('password')) : ?>
	<li><label for="password" class="label"><?php echo JText::_( 'Password: ' ); ?></label>
	<input class="text-input validate-password" type="password" id="password" name="password" value="" size="40" /></li>
	
	<li><label for="password2" class="label"><?php echo JText::_( 'Verify Password: ' ); ?></label>
	<input class="text-input validate-passverify" type="password" id="password2" name="password2" size="40" /></li>
	<?php endif; ?>

	<?php if(isset($this->params)) :  echo $this->params->render( 'params' ); endif; ?>
	<li class="submit"><button class="button validate" type="submit" onclick="submitbutton( this.form );return false;"><?php echo JText::_('Save'); ?></button></li>
</ul>	
	<input type="hidden" name="username" value="<?php echo $this->user->get('username');?>" />
	<input type="hidden" name="id" value="<?php echo $this->user->get('id');?>" />
	<input type="hidden" name="gid" value="<?php echo $this->user->get('gid');?>" />
	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="save" />
	<?php echo JHTML::_( 'form.token' ); ?>

</form>
</div>