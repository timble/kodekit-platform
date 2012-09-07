<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>

<h1><?=@text('Change your Password');?></h1>

<form action="" method="post" class="-koowa-form">
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Password') ?></label>

        <div class="controls">
            <input class="inputbox" type="password" id="password" name="password" value="" size="40"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password"><?= @text('Verify Password') ?></label>

        <div class="controls">
            <input class="inputbox" type="password" id="password_verify" name="password_verify" size="40"/>
        </div>
    </div>
    <div class="form-actions">
        <button class="btn validate" type="submit"><?= @text('Save') ?></button>
    </div>
    <input type="hidden" name="action" value="save" />
</form>