<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="login-wrap">
<h1><?php echo JText::_('Confirm your Account'); ?></h1>
<p class="login-description"><?php echo JText::_('RESET_PASSWORD_CONFIRM_DESCRIPTION'); ?></p>
<form action="<?php echo JRoute::_( 'index.php?option=com_user&amp;task=confirmreset' ); ?>" method="post" class="josForm form-validate login-confirm" id="login-form">
	<ul>
		<li><label for="token" class="tooltip" title="<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_TOKEN_TIP_TEXT'); ?>"><?php echo JText::_('Token'); ?>:</label>
		</li>
		<li><input id="token" name="token" type="text" class="required form-input" size="36" /></li>

		<li class="login-btn"><button type="submit" class="validate"><?php echo JText::_('Submit'); ?></button></li>
	</ul>
	<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>