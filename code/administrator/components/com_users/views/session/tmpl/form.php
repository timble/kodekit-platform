<?
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<h1><?= @text('Administration Login') ?></h1>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<form action="" method="post" name="login" id="form-login">
    <div class="control-group">
        <label class="control-label" for=""><?= @text( 'Email' ); ?></label>
        <div class="controls">
        <input name="email" id="email" type="text" class="inputbox" size="20" autofocus="autofocus" placeholder="<?= @text('Email') ?>" />
        </div>
    </div>
   <div class="control-group">
       <label class="control-label" for=""><?= @text( 'Password' ); ?></label>
       <div class="controls">
            <input name="password" type="password" id="password" class="inputbox" size="15" placeholder="<?= @text('Password') ?>" />
       </div>
   </div>
   <? if(@service('application')->getSite() == 'default') : ?>
   <div class="control-group">
       <label class="control-label" for="site"><?= @text('Site'); ?></label>
       <div class="controls">
            <?= @service('com://admin/sites.template.helper.listbox')->sites(array('attribs' => array('class' => 'chzn-select'))); ?>
       </div>
   </div>
   <? endif ?>
   <? if($error = JError::getError(true)) : ?>
   	<p id="login-error-message"><?= $error->get('message') ?></p>
   <? endif ?>
   <input type="submit" class="btn" value="<?= @text('Login') ?>" />
</form>