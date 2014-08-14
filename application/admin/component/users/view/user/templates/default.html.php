<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<ktml:script src="assets://js/koowa.js" />
<ktml:script src="assets://users/js/users.js" />
<ktml:style src="assets://css/koowa.css" />

<?= helper('behavior.validator') ?>

<script>
    window.addEvent('domready', function() {
        ComUsers.Form.addValidators(['passwordLength','passwordVerify']);
    });
</script>

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" id="user-form" class="-koowa-form">
	<input type="hidden" name="enabled" value="<?= $this->getObject('user')->getId() == $user->id ? 1 : 0 ?>" />
	
	<div class="main">
		<div class="title">
			<input class="required" type="text" id="name" name="name" value="<?= $user->name ?>" placeholder="<?= translate('Name') ?>" />
		</div>
		
		<div class="scrollable">
			<fieldset>
				<legend><?= translate('General') ?></legend>
				<div>
				    <label for="email"><?= translate('E-Mail') ?></label>
				    <div>
				        <input class="required validate-email" type="email" id="email" name="email" value="<?= $user->email ?>" />
				    </div>
				</div>
			</fieldset>
			<fieldset>
				<legend><?= translate('Password') ?></legend>
				<div>
				    <label for="password"><?= translate('Password') ?></label>
				    <div>
                        <input class="passwordLength:6" id="password" type="password" name="password" maxlength="100" />
				        <?= helper('com:users.form.password');?>
				    </div>
				</div>
				<div>
				    <label for="password_verify"><?= translate('Verify Password') ?></label>
				    <div>
				        <input class="passwordVerify matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify" maxlength="100" />
				    </div>
				</div>
			    <? if (!$user->isNew()): ?>
				<div>
				    <div>
				        <label class="checkbox" for="password_reset">
				            <input type="checkbox" id="password_reset" name="password_reset" />
				            <?= translate('Require a password reset for the next sign in') ?>
				        </label>
				    </div>
				</div>
			    <? endif; ?>
			</fieldset>
			<fieldset>
				<legend><?= translate('Locale') ?></legend>
                <div>
                    <label for="parameters[timezone]"><?= translate('Time Zone') ?></label>
                    <div>
                        <?= helper('listbox.timezones', array(
                            'name'     => 'parameters[timezone]',
                            'selected' => $user->getParameters()->timezone,
                            'deselect' => true,
                            'attribs'  => array('class' => 'select-timezone', 'style' => 'width:220px')
                        )) ?>
                    </div>
                </div>
                <div>
                    <label for="parameters[language]"><?= translate('Language') ?></label>
                    <div>
                        <?= helper('listbox.languages', array(
                            'name'     => 'parameters[language]',
                            'selected' => $user->getParameters()->language,
                            'deselect' => true,
                            'attribs'  => array('class' => 'select-language', 'style' => 'width:220px')
                        )) ?>
                    </div>
                </div>
			</fieldset>
		</div>
	</div>
	
	<div class="sidebar">
        <?= import('default_sidebar.html'); ?>
	</div>
</form>

<script data-inline> $jQuery(".select-timezone").select2(); </script>