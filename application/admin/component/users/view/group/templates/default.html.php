<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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