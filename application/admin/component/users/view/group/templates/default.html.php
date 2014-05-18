<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<!--
<script src="assets://js/koowa.js" />
<style src="assets://css/koowa.css" />
-->

<ktml:module position="actionbar">
    <ktml:toolbar type="actionbar">
</ktml:module>

<form action="" method="post" class="-koowa-form" id="group-form">
    <div class="main">
        <div class="title">
            <input class="required" type="text" name="name" maxlength="255" value="<?= $group->name ?>" placeholder="<?= translate('Group name') ?>" />
        </div>
        <div class="scrollable">
    		<fieldset>
    			<legend><?= translate('Users') ?></legend>
    			<div>
    			    <div>
    			        <?= helper('select.users', array('selected' => $users, 'name' => 'users')) ?>
    			    </div>
    			</div>
    		</fieldset>
        </div>
    </div>
</form>