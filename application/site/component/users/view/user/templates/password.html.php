<?php
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