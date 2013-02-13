<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?=@helper('behavior.mootools')?>
<?=@helper('behavior.validator')?>

<form action="<?= @route('option=com_users&view=session'); ?>" method="post" name="login" id="form-login" class="-koowa-form">
	<? if($show_title) : ?>
	<h3><?= $module->title ?></h3>
	<? endif ?>

	<fieldset class="input">
	<div class="control-group">
		<label class="control-label" for="modlgn_email"><?= @text('Email') ?>:</label>
		<div class="controls">
			<input id="modlgn_email" class="required validate-email" type="text" name="email" />
		</div>
	</div>
	<div class="control-group">
		<label class="control-label" for="modlgn_passwd"><?= @text('Password') ?>:</label>
		<div class="controls">
			<input id="modlgn_passwd" class="required" type="password" name="password" />
			<span class="help-block">
			    <small><a href="<?= @route( 'option=com_users&view=user&layout=reset' ); ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a></small>
			</span>
		</div>
	</div>
	<div class="form-actions">
		<input type="submit" name="Submit" class="btn" value="<?= @text('Sign in') ?>" />
		<?php if ($allow_registration) : ?>
			<?= @text('or') ?>
			<a href="<?= @route( 'option=com_users&view=user&layout=form' ); ?>"><?= @text('Sign up'); ?></a>
		<?php endif; ?>
	</div>
	</fieldset>
</form>