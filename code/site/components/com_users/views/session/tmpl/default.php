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

<div class="page-header">
    <h1><?= @escape($parameters->get('page_title')) ?></h1>
</div>

<form action="" method="post" id="com-form-login" class="form-horizontal">
    <? if($parameters->get('description_login_text')) : ?>
    <p><?= $parameters->get('description_login_text' ) ?></p>
    <? endif ?>
    
    <div class="control-group">
        <label class="control-label" for="username"><?= @text('Username') ?></label>
        <div class="controls">
            <input name="username" type="text" alt="username" />
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>
        <div class="controls">
            <input type="password" name="password" alt="password" />
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="validate btn btn-primary"><?= @text('Login') ?></button>
    </div>

    <ul>
        <li>
            <a href="<?= @route('view=reset') ?>">
            <?= @text('FORGOT_YOUR_PASSWORD') ?></a>
        </li>
        <? if($parameters->get('registration')) : ?>
        <li>
            <a href="<?= @route('view=user&layout=register') ?>">
                <?= @text('REGISTER') ?></a>
        </li>
        <? endif ?>
    </ul>
</form>