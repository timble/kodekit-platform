<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?=helper('behavior.mootools')?>
<?=helper('behavior.validator')?>

<script type="text/javascript">
    window.onDomReady(function() {
        $('recaptcha_response_field').addClass('required');
    });
</script>

<form action="<?=  helper('route.message', array('row' => $contact, 'category' => $category->getSlug())) ?>" method="post" name="emailForm" class="-koowa-form form-horizontal">
    <input type="hidden" name="_action" value="add" />
    <div class="control-group">
        <label class="control-label" for="name">
            <?= translate( 'Enter your name' );?>:
        </label>
        <div class="controls">
            <input type="text" name="name" value="<?=isset($captcha_data)?escape($captcha_data->name):''?>" class="required" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email">
            <?= translate( 'Email address' );?>:
        </label>
        <div class="controls">
            <input type="email" name="email" value="<?=isset($captcha_data)?escape($captcha_data->email):''?>" class="required validate-email" maxlength="100" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="subject">
            <?= translate( 'Message subject' );?>:
        </label>
        <div class="controls">
            <input type="text" name="subject" value="<?=isset($captcha_data)?escape($captcha_data->subject):''?>" required />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="text">
            <?= translate( 'Enter your message' );?>:
        </label>
        <div class="controls">
            <textarea rows="10" name="text" class="required" required><?=isset($captcha_data)?$captcha_data->text:''?></textarea>
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <label class="checkbox" for="email_copy">
                <input <?=isset($captcha_data) && $captcha_data->email_copy?'checked':''?> type="checkbox" name="email_copy" value="1" /> <?= translate( 'EMAIL_A_COPY' ); ?>
            </label>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <?=helper('com:users.captcha.render', array('attribs' => array('class' => 'required')))?>
        </div>
    </div>
    
    <div class="form-actions">
	    <button class="btn btn-primary validate" type="submit"><?= translate('Send'); ?></button>
	</div>
</form>
