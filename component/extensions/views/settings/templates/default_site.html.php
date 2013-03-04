<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<fieldset class="form-horizontal">
	<legend><?= @text( 'General' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][sitename]"><?= @text( 'Site Name' ); ?></label>
	    <div class="controls">
	        <input type="text" name="settings[system][sitename]" value="<?= $settings->sitename; ?>" />
	        <p class="help-block"><?= @text( 'TIPSITENAME' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][offline]"><?= @text( 'Require login' ); ?></label>
	    <div class="controls">
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][offline]', 'selected' => $settings->offline));?>
	        <p class="help-block"><?= @text( 'TIPSETYOURSITEREQUIRESLOGIN' ); ?></p>
	    </div>
	</div>
</fieldset>		
		
<fieldset class="form-horizontal">
	<legend><?= @text( 'Defaults' ); ?></legend>
	<div class="control-group">
	    <label class="control-label" for="settings[system][list_limit]"><?= @text( 'List Length' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.list_limits', array('name' => 'settings[system][list_limit]', 'selected' => $settings->list_limit)); ?>
	        <p class="help-block"><?= @text( 'TIPSETSDEFAULTLENGTHLISTS' ); ?></p>
	    </div>
	</div>
	<div class="control-group">
	    <label class="control-label" for="settings[system][feed_limit]"><?= @text( 'Feed Length' ); ?></label>
	    <div class="controls">
	        <?= @helper('listbox.list_limits', array('name' => 'settings[system][feed_limit]', 'selected' => $settings->feed_limit)); ?>
	        <p class="help-block"><?= @text( 'TIPFEEDLIMIT' ); ?></p>
	    </div>
	</div>
</fieldset>
