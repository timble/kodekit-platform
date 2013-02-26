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

<form action="<?= @route('view=message&id='.$id) ?>" method="post" name="emailForm" class="form-validate form-horizontal">
    <input type="hidden" name="_action" value="add" />
    <div class="control-group">
        <label class="control-label" for="name">
            <?= @text( 'Enter your name' );?>:
        </label>
        <div class="controls">
            <input type="text" name="name" value="" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">
            <?= @text( 'Email address' );?>:
        </label>
        <div class="controls">
            <input type="text" name="email" value="" class="required validate-email" maxlength="100" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="subject">
            <?= @text( 'Message subject' );?>:
        </label>
        <div class="controls">
            <input type="text" name="subject" value="" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="text">
            <?= @text( 'Enter your message' );?>:
        </label>
        <div class="controls">
            <textarea rows="10" name="text" class="required"></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox" for="email_copy">
                <input type="checkbox" name="email_copy" value="1" /> <?= @text( 'EMAIL_A_COPY' ); ?>
            </label>
        </div>
    </div>
    
    <div class="form-actions">
	    <button class="btn btn-primary validate" type="submit"><?= @text('Send'); ?></button>
	</div>
</form>
