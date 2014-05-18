<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<h1><?= translate('Administrator Login') ?></h1>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<form action="" method="post" name="login" id="form-login">
    <input name="email" id="email" type="email" class="inputbox" autofocus="autofocus" placeholder="<?= translate('Email') ?>" />
    <input name="password" type="password" id="password" class="inputbox" placeholder="<?= translate('Password') ?>" />

   <input type="submit" class="btn btn-large btn-block" value="<?= translate('Login') ?>" />
   <p><a class="return" href="/"><?= translate('Go to site homepage.'); ?></a></p>
</form>