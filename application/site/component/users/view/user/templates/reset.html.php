<?php
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
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