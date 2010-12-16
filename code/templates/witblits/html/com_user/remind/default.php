<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="user-remind">
	<div id="login-wrap">
	<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	<?php endif; ?>
	<p class="login-description"><?php echo JText::_('REMIND_USERNAME_DESCRIPTION'); ?></p>
	<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=remindusername' ); ?>" method="post" class="josForm form-validate forgot-pass" id="login-form">
	<ul>
		<li class="label"><label for="email" class="tooltip" title="<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('REMIND_USERNAME_EMAIL_TIP_TEXT'); ?>"><?php echo JText::_('Email Address'); ?>:</label>
		<span class="input-wrap"><input id="email" name="email" type="text" class="required validate-email form-input" /></span></li>
		<li class="login-btn">
			<input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('Submit') ?>" />
		</li>
		<?php echo JHTML::_( 'form.token' ); ?>
	</ul>
	</form>
	</div>
</div>