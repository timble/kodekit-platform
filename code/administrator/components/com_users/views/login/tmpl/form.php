<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<h1><?= @text('Administration Login') ?></h1>

<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<?= @helper('behavior.keepalive'); ?>

<form action="<?= @route('view=user') ?>" method="post" name="login" id="form-login">
    <input type="hidden" name="action" value="login" />

    <p id="form-login-username">
        <label for="username">
            <?= @text('Username') ?><br />
            <input name="username" id="username" type="text" class="inputbox" size="20" autofocus="autofocus" placeholder="<?= @text('Username') ?>" />
        </label>
    </p>

    <p id="form-login-password">
        <label for="password"><?= @text('Password') ?></label><br />
        <input name="password" type="password" id="password" class="inputbox" size="15" placeholder="<?= @text('Password') ?>" />
    </p>
    <p id="form-login-site">
     	<label for="modlgn_site"><?php echo JText::_('Site'); ?></label><br />
        <?= KTemplateHelper::factory('admin::com.sites.template.helper.listbox')->sites(array('attribs' => array('class' => 'inputbox'))); ?>
  	</p>
    <? if($error = JError::getError(true)) : ?>
        <p id="login-error-message"><?= $error->get('message') ?></p>
    <? endif ?>
    <div class="button_holder">
        <div class="button1">
            <a onclick="login.submit();">
                <?= @text('Login') ?>
            </a>
        </div>
    </div>
    <div class="clr"></div>
    <input type="submit" style="border: 0; padding: 0; margin: 0; width: 0px; height: 0px;" value="<?= @text('Login') ?>" />
</form>