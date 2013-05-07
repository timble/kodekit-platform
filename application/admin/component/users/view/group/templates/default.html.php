<?
/**
 * @package     Nooku_Server
 * @subpackage  Users
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('behavior.validator') ?>

<!--
<script src="media://js/koowa.js" />
<style src="media://css/koowa.css" />
-->

<ktml:module position="toolbar">
    <?= @helper('toolbar.render', array('toolbar' => $toolbar))?>
</ktml:module>

<form action="" method="post" class="-koowa-form" id="group-form">
    <div class="main">
        <div class="title">
            <input class="required" type="text" name="name" maxlength="255" value="<?= $group->name ?>" placeholder="<?= @text('Group name') ?>" />
        </div>
        <div class="scrollable">
    		<fieldset>
    			<legend><?= @text('Users') ?></legend>
    			<div>
    			    <div>
    			        <?= @helper('select.users', array('selected' => $users, 'name' => 'users')) ?>
    			    </div>
    			</div>
    		</fieldset>
        </div>
    </div>
</form>