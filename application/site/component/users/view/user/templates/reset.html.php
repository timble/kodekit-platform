<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?=helper('behavior.mootools')?>
<?=helper('behavior.validator')?>

<div class="page-header">
    <h1><?=translate('Password reset request');?></h1>
</div>

<p><?= translate('Please enter the E-mail address of the account you would like to reset.');?></p>
<form action="" method="post" class="-koowa-form">
    <div class="form-group">
        <label for="email"><?= translate('E-mail') ?></label>
        <input class="form-control required validate-email" type="email" id="email" name="email" placeholder="E-mail"/>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?=translate('Submit');?></button>
    </div>
    <input type="hidden" name="_action" value="token"/>
</form>