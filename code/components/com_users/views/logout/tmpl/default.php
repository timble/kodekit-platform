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

<form action="<?= @route() ?>" method="post" name="login" id="login">
    <input type="hidden" name="action" value="logout" />

    <? if($parameters->get('show_logout_title')) : ?>
        <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
            <?= @escape($parameters->get('header_logout')) ?>
        </div>
    <? endif ?>
    <table border="0" align="center" cellpadding="4" cellspacing="0" class="contentpane<?= @escape($parameters->get('pageclass_sfx')) ?>" width="100%">
        <tr>
            <td valign="top">
                <div>
                    <? if($parameters->get('image_logout')) : ?>
                        <? $image = 'images/stories/'.$parameters->get('image_logout') ?>
                        <img src="<?= $image ?>" align="<?= $parameters->get('image_logout_align') ?>" hspace="10" alt="" />
                    <? endif ?>
                    <? if($parameters->get('description_logout')) : ?>
                        <?= @escape($parameters->get('description_logout_text')) ?>
                    <? endif ?>
                </div>
            </td>
        </tr>
        <tr>
            <td align="center">
                <div align="center">
                    <input type="submit" name="Submit" class="button" value="<?= @text('Logout') ?>" />
                </div>
            </td>
        </tr>
    </table>
</form>