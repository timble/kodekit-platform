<?
/**
 * @version     $Id: default.php 843 2011-04-06 21:06:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
-->
<?= @helper('behavior.keepalive'); ?>

<? if($parameters->get('show_page_title', 1)) : ?>
<h1 class="page-header"><?= @escape($parameters->get('page_title')) ?></h1>
<? endif ?>

<form action="" method="post" id="com-form-login" class="form-horizontal">
    <? if($parameters->get('show_login_title')) : ?>
    <p><?= $parameters->get('header_login') ?></p>
    <? endif ?>
    <? if($parameters->get('description_login')) : ?>
    <p><?= $parameters->get('description_login_text' ) ?></p>
    <? endif ?>
    
    <div class="control-group">
        <label class="control-label" for="username"><?= @text('Username') ?></label>
        <div class="controls">
            <input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input type="password" id="password" name="password" class="inputbox" size="18" alt="password" />
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="validate btn"><?= @text('Submit') ?></button>
    </div>

    <ul>
        <li>
            <a href="<?= @route('view=reset') ?>">
            <?= @text('FORGOT_YOUR_PASSWORD') ?></a>
        </li>
        <li>
            <a href="<?= @route('view=remind') ?>">
            <?= @text('FORGOT_YOUR_USERNAME') ?></a>
        </li>
        <? if($parameters->get('registration')) : ?>
        <li>
            <a href="<?= @route('view=user&layout=register') ?>">
                <?= @text('REGISTER') ?></a>
        </li>
        <? endif ?>
    </ul>
</form>