<?php
/**
 * @version     $Id: default.php 843 2011-04-06 21:06:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<? if($parameters->get('show_page_title', 1)) : ?>
    <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
        <?= @escape($parameters->get('page_title')) ?>
    </div>
<? endif ?>

<form action="<?= @route() ?>" method="post" id="com-form-login">
    <input type="hidden" name="action" value="login" />
    <? if ($return): ?>
    <input type="hidden" name="return" value="<?=$return?>" />
    <? endif ?>

    <table width="100%" border="0" align="center" cellpadding="4" cellspacing="0" class="contentpane<?= @escape($parameters->get('pageclass_sfx')) ?>">
        <tr>
            <td colspan="2">
                <? if($parameters->get('show_login_title')) : ?>
                    <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
                        <?= $parameters->get('header_login') ?>
                    </div>
                <? endif ?>
                <div>
                    <? if($parameters->get('image_login')) : ?>
                        <? $image = 'images/stories/'.$parameters->get('image_login') ?>
                         <img src="<?= $image ?>" align="<?= $parameters->get('image_login_align') ?>" hspace="10" alt="" />
                    <? endif ?>
                    <? if($parameters->get('description_login')) : ?>
                        <?= $parameters->get('description_login_text' ) ?>
                        <br /><br />
                    <? endif ?>
                </div>
            </td>
        </tr>
    </table>

    <fieldset class="input">
        <p id="com-form-login-username">
            <label for="username"><?= @text('Username') ?></label><br />
            <input name="username" id="username" type="text" class="inputbox" alt="username" size="18" />
        </p>
        <p id="com-form-login-password">
            <label for="password"><?= @text('Password') ?></label><br />
            <input type="password" id="password" name="password" class="inputbox" size="18" alt="password" />
        </p>
        <input type="submit" name="Submit" class="button" value="<?= @text('LOGIN') ?>" />
    </fieldset>

    <ul>
        <li>
            <a href="<?= @route('index.php?option=com_users&view=reset') ?>">
            <?= @text('FORGOT_YOUR_PASSWORD') ?></a>
        </li>
        <li>
            <a href="<?= @route('index.php?option=com_users&view=remind') ?>">
            <?= @text('FORGOT_YOUR_USERNAME') ?></a>
        </li>
        <? if($parameters->get('registration')) : ?>
        <li>
            <a href="<?= @route('index.php?option=com_user&view=register') ?>">
                <?= @text('REGISTER') ?></a>
        </li>
        <? endif ?>
    </ul>
</form>