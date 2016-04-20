<?
/**
 * Kodekit Platform - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-platform for the canonical source repository
 */
?>

<?= helper('behavior.kodekit'); ?>

<ktml:messages>
<div class="login-box">
    <h1><?= translate('Administrator Login') ?></h1>

    <form action="" method="post" name="login" id="form-login">
        <input name="email" id="email" type="email" class="inputbox" autofocus="autofocus" placeholder="<?= translate('Email') ?>" />
        <input name="password" type="password" id="password" class="inputbox" placeholder="<?= translate('Password') ?>" />

        <input type="submit" class="button btn-large btn-block" value="<?= translate('Login') ?>" />
        <p><a class="return" href="/"><?= translate('Go to site homepage.'); ?></a></p>
    </form>
</div>
