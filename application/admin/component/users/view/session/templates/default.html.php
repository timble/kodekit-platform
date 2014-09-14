<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<h1><?= translate('Administrator Login') ?></h1>

<ktml:script src="assets://js/koowa.js" />
<ktml:style src="assets://css/koowa.css" />

<form action="" method="post" name="login" id="form-login">
    <input name="email" id="email" type="email" class="inputbox" autofocus="autofocus" placeholder="<?= translate('Email') ?>" />
    <input name="password" type="password" id="password" class="inputbox" placeholder="<?= translate('Password') ?>" />

   <input type="submit" class="button btn-large btn-block" value="<?= translate('Login') ?>" />
   <p><a class="return" href="/"><?= translate('Go to site homepage.'); ?></a></p>
</form>