<?php 
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<? if($parameters->def('show_page_title', 1)) : ?>
<h1 class="page-header"><?= @escape($parameters->get('page_title')) ?></h1>
<? endif ?>

<form action="" method="post" class="josForm form-validate form-horizontal">
    <input type="hidden" name="action" value="request" />
    
    <p><?= @text('RESET_PASSWORD_REQUEST_DESCRIPTION') ?></p>
    
    <div class="control-group">
        <label class="control-label" for="email"><?= @text('Email Address') ?></label>
        <div class="controls">
            <input id="email" name="email" type="text" class="required validate-email" />
            <p class="help-block"><?= @text('RESET_PASSWORD_EMAIL_TIP_TEXT') ?></p>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="validate btn"><?= @text('Submit') ?></button>
    </div>
</form>