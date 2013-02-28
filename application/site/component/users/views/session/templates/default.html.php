<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?=@helper('behavior.mootools')?>
<?=@helper('behavior.keepalive')?>
<?=@helper('behavior.validator')?>

<div class="page-header">
    <h1><?= @escape($parameters->get('page_title')) ?></h1>
</div>

<form action="" method="post" id="com-form-login" class="-koowa-form form-horizontal">
    <? if($parameters->get('description_login_text')) : ?>
    <p><?= $parameters->get('description_login_text' ) ?></p>
    <? endif ?>
    
    <div class="control-group">
        <label class="control-label" for="email"><?= @text('E-mail') ?></label>
        <div class="controls">
            <input id="email" class="required validate-email" name="email" type="email" alt="email" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input id="password" class="required" type="password" name="password" alt="password" />
            <span class="help-block">
                <small><a href="<?= @route('view=password&layout=token') ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a></small>
            </span>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="validate btn btn-primary"><?= @text('Sign in') ?></button>
        <? if($parameters->get('registration')) : ?>
        	<?= @text('or') ?>
        	<a href="<?= @route('view=user&layout=form') ?>"><?= @text('Sign up'); ?></a>
        <?php endif; ?>
    </div>
</form>