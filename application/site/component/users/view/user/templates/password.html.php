<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.koowa'); ?>
<?= helper('behavior.validator');?>

<div class="page-header">
    <h1><?= translate('Set your password');?></h1>
</div>

<form action="" method="post" class="-koowa-form">
    <div class="form-group">
        <label for="password"><?= translate('Password') ?></label>
        <input class="form-control minLength:<?= $password_length ?>" type="password" id="password" name="password" value=""/>
        <?= helper('com:users.form.password');?>
    </div>
    <div class="form-group">
        <label for="password"><?= translate('Verify Password') ?></label>
        <input class="form-control validate-match required matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify"/>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= translate('Save') ?></button>
    </div>
    <? if (isset($token)): ?>
    <input type="hidden" name="token" value="<?=$token;?>"/>
    <? endif;?>
    <input type="hidden" name="_action" value="<?= isset($token) ? 'reset' : 'save';?>"/>
</form>