<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Banners HTML template - Client form
 *
 * @author      Cristiano Cucco <cristiano.cucco at gmail dot com>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Banners    
 */
 
defined('KOOWA') or die('Restricted access'); ?>

<?= @helper('behavior.tooltip');?>

<script src="media://system/js/mootools.js" />  
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />

<form action="<?=@route('id='.$client->id)?>" method="post" class="-koowa-form">

    <div class="grid_5">
        <fieldset class="adminform">
            <legend><?=@text( 'Details' ); ?></legend>

            <table class="admintable">
            <tbody>
                <tr>
                    <td width="20%" class="key">
                        <label for="name">
                            <?=@text( 'Client Name' ); ?>:
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" name="name" id="name" 
                        size="50" value="<?= $client->name;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="key">
                        <label for="contact">
                            <?=@text( 'Contact Name' ); ?>:
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" name="contact" id="contact" 
                        size="50" value="<?= $client->contact;?>" />
                    </td>
                </tr>
                <tr>
                    <td width="20%" class="key">
                        <label for="email">
                            <?=@text( 'Contact E-mail' ); ?>:
                        </label>
                    </td>
                    <td width="80%">
                        <input class="inputbox" type="text" name="email" id="email" 
                        size="50" value="<?= $client->email;?>" />
                    </td>
                </tr>
            </tbody>
            </table>
        </fieldset>
    </div>
    <div class="grid_7">
        <fieldset class="adminform">
            <legend><?=@text( 'Extra Information' ); ?></legend>
            <textarea rows="10" cols="40" name="extrainfo" id="extrainfo" 
            style="width:90%""><?=$client->extrainfo?></textarea>
        </fieldset>
    </div>
    <div class="clr"></div>
</form>
                