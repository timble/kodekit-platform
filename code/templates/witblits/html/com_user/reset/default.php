<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="user-reset">
	<div id="login-wrap">
	<?php if ( $this->params->def( 'show_page_title', 1 ) ) : ?>
		<h1><?php echo $this->escape($this->params->get('page_title')); ?></h1>
	<?php endif; ?>
	<p class="login-description"><?php echo JText::_('RESET_PASSWORD_REQUEST_DESCRIPTION'); ?></p>
	<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=requestreset' ); ?>" method="post" id="login-form" class="josForm form-validate forgot-pass">
	<ul>
		<li class="label"><label for="email" class="tooltip" title="<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_EMAIL_TIP_TEXT'); ?>"><?php echo JText::_('Email Address'); ?>:</label>
			<span class="input-wrap"><input id="email" name="email" type="text" class="required validate-email form-input" /></span></li>
		<li class="login-btn"><input type="submit" name="Submit" class="button validate" value="<?php echo JText::_('Submit') ?>" /></li>
	</ul>
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
	</div>
</div>