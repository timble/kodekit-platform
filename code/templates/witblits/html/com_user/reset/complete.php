<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<div id="login-wrap">
<h1><?php echo JText::_('Reset your Password'); ?></h1>
<p><?php echo JText::_('RESET_PASSWORD_COMPLETE_DESCRIPTION'); ?></p>

<form action="<?php echo JRoute::_( 'index.php?option=com_user&task=completereset' ); ?>" method="post" class="josForm form-validate">
		<ul>
			<li class="label"><label for="password1" class="tooltip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD1_TIP_TEXT'); ?>"><?php echo JText::_('Password'); ?>:</label>
				<input id="password1" name="password1" type="password" class="required validate-password form-input" />
			</li>
			<li class="label"><label for="password2" class="tooltip" title="<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TITLE'); ?>::<?php echo JText::_('RESET_PASSWORD_PASSWORD2_TIP_TEXT'); ?>"><?php echo JText::_('Verify Password'); ?>:</label>
				<input id="password2" name="password2" type="password" class="required validate-password form-input" />
			</li>
			<li class="login-btn">
				<button type="submit" class="validate"><?php echo JText::_('Submit'); ?></button>
			</li>
		</ul>
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>