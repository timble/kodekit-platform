<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */
defined('KOOWA') or die('Restricted access');
?>

<?=@helper('behavior.mootools')?>
<?=@helper('behavior.validator')?>

<div class="page-header">
    <h1><?=@text('Password reset request');?></h1>
</div>

<p><?= @text('RESET_PASSWORD_REQUEST_DESCRIPTION');?></p>
<form action="" method="post" class="-koowa-form form-horizontal">
    <div class="control-group">
        <label class="control-label" for="email"><?= @text('E-mail') ?></label>

        <div class="controls">
            <input class="required validate-email" type="text" id="email" name="email" placeholder="E-mail"/>
        </div>
    </div>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary"><?=@text('Submit');?></button>
    </div>
    <input type="hidden" name="action" value="token"/>
</form>