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

<? if($parameters->def('show_page_title', 1)) : ?>
    <div class="componentheading<?= @escape($parameters->get('pageclass_sfx')) ?>">
        <?= @escape($parameters->get('page_title')) ?>
    </div>
<? endif ?>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <?= nl2br(@escape($parameters->get('welcome_desc', @text('WELCOME_DESC')))) ?>
        </td>
    </tr>
</table>