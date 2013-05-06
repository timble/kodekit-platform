<?
/**
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h1><?= @text('Administrator Login') ?></h1>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<form action="" method="post" name="login" id="form-login">
    <input name="email" id="email" type="email" class="inputbox" autofocus="autofocus" placeholder="<?= @text('Email') ?>" />
    <input name="password" type="password" id="password" class="inputbox" placeholder="<?= @text('Password') ?>" />

   <input type="submit" class="btn btn-large btn-block" value="<?= @text('Login') ?>" />
   <p><a class="return" href="/"><?= @text('Go to site homepage.'); ?></a></p>
</form>