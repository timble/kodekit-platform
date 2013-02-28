<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>

<?=@helper('behavior.mootools');?>
<?=@helper('behavior.validator');?>

<script src="media://koowa/js/koowa.js"/>

<div class="page-header">
    <h1><?=@text('Set your password');?></h1>
</div>

<form action="" method="post" class="-koowa-form form-horizontal">
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>

        <div class="controls">
            <input class="minLength:<?=$parameters->get('password_length', 6);?>" type="password" id="password" name="password" value=""/>
            <?=@helper('com://admin/users.template.helper.form.password');?>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Verify Password') ?></label>

        <div class="controls">
            <input class="validate-match matchInput:'password' matchName:'password'" type="password" id="password_verify" name="password_verify"/>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn btn-primary validate" type="submit"><?= @text('Save') ?></button>
    </div>
    <? if (isset($reset)): ?>
    <input type="hidden" name="reset" value="<?=$reset;?>"/>
    <? endif;?>
    <input type="hidden" name="action" value="<?=isset($reset) ? 'reset' : 'save';?>"/>
</form>