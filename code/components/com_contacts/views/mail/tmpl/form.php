<?
/**
 * @version		$Id: default.php 3537 2012-04-02 17:56:59Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

defined('KOOWA') or die('Restricted access'); ?>

<script>
	function validateForm( frm ) {
    	var valid = document.formvalidator.isValid(frm);
        if (valid == false) {
        	if (frm.email.invalid) {
            	alert( "' . @text( 'Please enter a valid e-mail address.', true ) . '" );
            } else if (frm.text.invalid) {
                alert( "' . @text( 'CONTACT_FORM_NC', true ) . '" );
            }
            return false;
         } else {
        	frm.submit();
         }
	}
</script>

<form action="" method="post" name="emailForm" id="emailForm" class="form-validate">
 <input type="hidden" name="action" value="email" />
<div class="contact_email">
 	<label for="contact_name">
        <?= @text( 'Enter your name' );?>:
	</label>
	<br />
	<input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
	<br />
	<label id="contact_emailmsg" for="contact_email">
        <?= @text( 'Email address' );?>:
	</label>
	<br />
 	<input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
    <br />
	<label for="contact_subject">
        <?= @text( 'Message subject' );?>:
	</label>
	<br />
	<input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
	<br /><br />
	<label id="contact_textmsg" for="contact_text">
        <?= @text( 'Enter your message' );?>:
	</label>
	<br />
	<textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
    <? /*if ($contact->params->get( 'show_email_copy' )) :*/ ?>
	<br />
	<input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  />
	<label for="contact_email_copy">
        <?= @text( 'EMAIL_A_COPY' ); ?>
    </label>
    <? /*endif;*/ ?>
    <br />
    <br />
    <button class="button validate" type="submit"><?= @text('Send'); ?></button>
</div>
</form>
