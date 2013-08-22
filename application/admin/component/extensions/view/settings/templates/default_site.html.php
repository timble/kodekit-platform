<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<fieldset>
	<legend><?= translate( 'General' ); ?></legend>
	<div>
	    <label for="settings[system][sitename]"><?= translate( 'Site Name' ); ?></label>
	    <div>
	        <input type="text" name="settings[system][sitename]" value="<?= $settings->sitename; ?>" />
	        <p class="help-block"><?= translate( 'TIPSITENAME' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][offline]"><?= translate( 'Require login' ); ?></label>
	    <div>
	        <?= helper('select.booleanlist' , array('name' => 'settings[system][offline]', 'selected' => $settings->offline));?>
	        <p class="help-block"><?= translate( 'TIPSETYOURSITEREQUIRESLOGIN' ); ?></p>
	    </div>
	</div>
</fieldset>		
		
<fieldset>
	<legend><?= translate( 'Defaults' ); ?></legend>
	<div>
	    <label for="settings[system][list_limit]"><?= translate( 'List Length' ); ?></label>
	    <div>
	        <?= helper('listbox.list_limits', array('name' => 'settings[system][list_limit]', 'selected' => $settings->list_limit)); ?>
	        <p class="help-block"><?= translate( 'TIPSETSDEFAULTLENGTHLISTS' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][feed_limit]"><?= translate( 'Feed Length' ); ?></label>
	    <div>
	        <?= helper('listbox.list_limits', array('name' => 'settings[system][feed_limit]', 'selected' => $settings->feed_limit)); ?>
	        <p class="help-block"><?= translate( 'TIPFEEDLIMIT' ); ?></p>
	    </div>
	</div>
</fieldset>
