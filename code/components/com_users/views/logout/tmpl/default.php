<?php 
/**
 * @version     $Id: default.php 843 2011-04-06 21:06:44Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<? if($parameters->get('show_page_title', 1)) : ?>
<h1 class="page-header"><?= @escape($parameters->get('page_title')) ?></h1>
<? endif ?>

<form action="<?= @route('view=user&id='.$user->id) ?>" method="post" name="login" id="login">
    <input type="hidden" name="action" value="logout" />

    <? if($parameters->get('show_logout_title')) : ?>
    <p><?= @escape($parameters->get('header_logout')) ?></p>
    <? endif ?>
    
    <? if($parameters->get('description_logout')) : ?>
    <p><?= @escape($parameters->get('description_logout_text')) ?></p>
    <? endif ?>
    
    <div class="form-actions">
        <input type="submit" name="Submit" class="btn" value="<?= @text('Logout') ?>" />
    </div>

</form>