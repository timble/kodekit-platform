<?
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */
?>

<?= helper('behavior.validator') ?>

<ktml:script src="assets://js/koowa.js" />
<ktml:style src="assets://css/koowa.css" />

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
    			        <?= helper('select.users', array('selected' => $selected, 'name' => 'users')) ?>
    			    </div>
    			</div>
    		</fieldset>
        </div>
    </div>
</form>