<?php /** $Id$ */
defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<?php
	$script = '<!--
		function validateForm( frm ) {
			var valid = document.formvalidator.isValid(frm);
			if (valid == false) {
				// do field validation
				if (frm.email.invalid) {
					alert( "' . JText::_( 'Please enter a valid e-mail address.', true ) . '" );
				} else if (frm.text.invalid) {
					alert( "' . JText::_( 'CONTACT_FORM_NC', true ) . '" );
				}
				return false;
			} else {
				frm.submit();
			}
		}
		// -->';
	
	$document =& JFactory::getDocument();
	$document->addScriptDeclaration($script);
	
	if(isset($this->error)) : ?>
<div id="message">
<?php echo $this->error; ?>
</div>
<?php endif; ?>

<div id="contact-form">
<?php if ($this->contact->params->get( 'email_description' ) !== '' ) : ?>
<p class="form-description"><?php echo $this->contact->params->get( 'email_description' ); ?></p>
<?php endif; ?>

	<form action="<?php echo JRoute::_( 'index.php' );?>" method="post" name="emailForm" id="emailForm" class="form-validate">
		<div class="contact_email">
			<label for="contact_name" class="label-name label-row">
				<?php echo JText::_( 'Enter your name' );?>:
			</label>
			<span class="input-wrap">
				<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
			</span>
			
			<label id="contact_emailmsg" for="contact_email" class="label-email label-row">
				<?php echo JText::_( 'Email address' );?>:
			</label>
			<span class="input-wrap">
				<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
			</span>
			
			<label for="contact_subject" class="label-subject label-row">
				<?php echo JText::_( 'Message subject' );?>:
			</label>
			<span class="input-wrap">
				<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
			</span>
			
			<div id="message-wrap">
			<label id="contact_textmsg" for="contact_text" class="label-message">
				<?php echo JText::_( 'Enter your message' );?>:
			</label>
			<span class="input-wrap">
				<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
			</span>
			</div>
			
			<?php if ($this->contact->params->get( 'show_email_copy' )) : ?>
			<p class="email-copy"><input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
			<label for="contact_email_copy">
				<?php echo JText::_( 'EMAIL_A_COPY' ); ?>
			</label></p>
			<?php endif; ?>
			
			<button class="button validate" type="submit"><?php echo JText::_('Send message'); ?></button>
		</div>

		<input type="hidden" name="option" value="com_contact" />
		<input type="hidden" name="view" value="contact" />
		<input type="hidden" name="id" value="<?php echo $this->contact->id; ?>" />
		<input type="hidden" name="task" value="submit" />
		<?php echo JHTML::_( 'form.token' ); ?>
	</form>
</div>