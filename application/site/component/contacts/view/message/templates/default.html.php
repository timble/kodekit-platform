<?
/**
 * @package     Nooku_Server
 * @subpackage  Contacts
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
?>

<?=@helper('behavior.mootools')?>
<?=@helper('behavior.validator')?>

<form action="<?=  @helper('route.message', array('row' => $contact, 'category' => $category->getSlug())) ?>" method="post" name="emailForm" class="-koowa-form form-horizontal">
    <input type="hidden" name="_action" value="add" />
    <div class="control-group">
        <label class="control-label" for="name">
            <?= @text( 'Enter your name' );?>:
        </label>
        <div class="controls">
            <input type="text" name="name" value="" class="required" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">
            <?= @text( 'Email address' );?>:
        </label>
        <div class="controls">
            <input type="email" name="email" value="" class="required validate-email" maxlength="100" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="subject">
            <?= @text( 'Message subject' );?>:
        </label>
        <div class="controls">
            <input type="text" name="subject" value="" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="text">
            <?= @text( 'Enter your message' );?>:
        </label>
        <div class="controls">
            <textarea rows="10" name="text" class="required" required></textarea>
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
