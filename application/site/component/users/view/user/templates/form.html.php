<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?=helper('behavior.mootools');?>
<?=helper('behavior.validator');?>

<ktml:script src="assets://js/koowa.js"/>
<ktml:script src="assets://users/js/users.js" />

<script>
    window.addEvent('domready', function () {
        ComUsers.Form.addValidators(['passwordLength','passwordVerify']);
    });
</script>

<form action="" method="post" autocomplete="off" class="-koowa-form">
    <div class="form-group">
        <label for="name"><?= translate('Your Name') ?></label>
        <input class="form-control required" type="text" id="name" name="name" value="<?= escape($user->name) ?>" size="100" />
    </div>

    <div class="form-group">
        <label for="email"><?= translate('Email') ?></label>
        <input class="form-control required validate-email" type="email" id="email" name="email" value="<?= escape($user->email) ?>" size="100" />
    </div>

    <div class="form-group">
        <label for="password"><?= translate('Password') ?></label>
        <input class="form-control <?=!$user->isNew()?:'required'?> passwordLength:<?= $password_length ?>" type="password" id="password" name="password" value="" size="40" />
        <?= helper('com:users.form.password');?>
    </div>

    <div class="form-group">
        <label for="password_verify"><?= translate('Verify Password') ?></label>
        <input class="form-control <?= !$user->isNew()?:'required'?> passwordVerify matchInput:'password' matchName:'password'" type="password" id="password_verify" size="40" />
    </div>

    <? if(!$user->isNew()): ?>
    <div class="form-group">
        <label class="control-label"><?=translate('Timezone');?></label>
        <?= helper('listbox.timezones', array(
            'name'     => 'timezone',
            'selected' => $user->timezone,
            'deselect' => true)
        );?>
    </div>
    <? endif;?>

    <input type="hidden" name="action" value="save" />

    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= translate('Save') ?></button>
    </div>
</form>