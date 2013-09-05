<?php
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

<div class="page-header">
    <h1><?=translate('Password reset request');?></h1>
</div>

<p><?= translate('RESET_PASSWORD_REQUEST_DESCRIPTION');?></p>
<form action="" method="post" class="-koowa-form form-horizontal">
    <div class="control-group">
        <label class="control-label" for="email"><?= translate('E-mail') ?></label>

        <div class="controls">
            <input class="required validate-email" type="email" id="email" name="email" placeholder="E-mail"/>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?=translate('Submit');?></button>
    </div>
    <input type="hidden" name="_action" value="token"/>
</form>