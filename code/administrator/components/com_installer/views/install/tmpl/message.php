<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Installer
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access'); ?>
    
<table class="adminform">
    <tbody>
    <? if($state->message) : ?>
        <tr>
            <th><?= @text($state->message) ?></th>
        </tr>
    <? endif ?>
    <? if($state->extension_message) : ?>
        <tr>
            <td><?= $state->extension_message ?></td>
        </tr>
    <? endif ?>
    </tbody>
</table>
