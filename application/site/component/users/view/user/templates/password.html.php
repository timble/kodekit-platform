<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?=helper('behavior.mootools');?>
<?=helper('behavior.validator');?>

<script src="assets://js/koowa.js"/>

<div class="page-header">
    <h1><?= translate('Set your password');?></h1>
</div>

<form action="" method="post" class="-koowa-form form-horizontal">
    <div class="control-group">
        <label class="control-label" for="password"><?= translate('Password') ?></label>

        <div class="controls">
            <input class="minLength:<?= $parameters->get('password_length', 6); ?>" type="password" id="password" name="password" value=""/>
            <?= helper('com:users.form.password');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password"><?= translate('Verify Password') ?></label>

        <div class="controls">
            <input class="validate-match required matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify"/>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= translate('Save') ?></button>
    </div>
    <? if (isset($token)): ?>
    <input type="hidden" name="token" value="<?=$token;?>"/>
    <? endif;?>
    <input type="hidden" name="_action" value="<?= isset($token) ? 'reset' : 'save';?>"/>
</form>