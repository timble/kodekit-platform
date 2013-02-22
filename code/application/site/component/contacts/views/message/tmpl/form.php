<?
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

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
    <div class="control-group">
        <label for="contact_name">
            <?= @text( 'Enter your name' );?>:
        </label>
        <div class="controls">
            <input type="text" name="name" id="contact_name" size="30" class="inputbox" value="" />
        </div>
    </div>
    <div class="control-group">
        <label id="contact_emailmsg" for="contact_email">
            <?= @text( 'Email address' );?>:
        </label>
        <div class="controls">
            <input type="text" id="contact_email" name="email" size="30" value="" class="inputbox required validate-email" maxlength="100" />
        </div>
    </div>
    <div class="control-group">
        <label for="contact_subject">
            <?= @text( 'Message subject' );?>:
        </label>
        <div class="controls">
            <input type="text" name="subject" id="contact_subject" size="30" class="inputbox" value="" />
        </div>
    </div>
    <div class="control-group">
        <label id="contact_textmsg" for="contact_text">
            <?= @text( 'Enter your message' );?>:
        </label>
        <div class="controls">
            <textarea cols="50" rows="10" name="text" id="contact_text" class="inputbox required"></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
             <input type="checkbox" name="email_copy" id="contact_email_copy" value="1"  /> <?= @text( 'EMAIL_A_COPY' ); ?>
        </div>
    </div>
    
    <div class="form-actions">
	    <button class="btn btn-primary validate" type="submit"><?= @text('Send'); ?></button>
	</div>
</form>
