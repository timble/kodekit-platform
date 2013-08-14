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
	<legend><?= @text( 'Diagnostics' ); ?></legend>
	<div>
	    <label for="settings[system][debug]"><?= @text( 'Application Profiling' ); ?></label>
	    <div>
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][debug]', 'selected' => $settings->debug));?>
	        <p class="help-block"><?= @text('TIPDEBUGGINGINFO'); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][debug_lang]"><?= @text( 'Language Indicators' ); ?></label>
	    <div>
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][debug_lang]', 'selected' => $settings->debug_lang));?>
	        <p class="help-block"><?= @text('TIPDEBUGLANGUAGE'); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= @text( 'Errors' ); ?></legend>
	<div>
	    <label for="settings[system][debug_mode]"><?= @text( 'Debug mode' ); ?></label>
	    <div>
	        <?= @helper('listbox.error_reportings', array('name' => 'settings[system][debug_mode]', 'selected' => $settings->debug_mode)); ?>
	        <p class="help-block"><?= @text( 'TIPERRORREPORTING' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= @text( 'Cache' ); ?></legend>
	<div>
	    <label for="settings[system][caching]"><?= @text( 'Cache' ); ?></label>
	    <div>
	        <?= @helper('select.booleanlist' , array('name' => 'settings[system][caching]', 'selected' => $settings->caching));?>
	        <p class="help-block"><?= @text( 'TIPCACHE' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][cachetime]"><?= @text( 'Cache Time' ); ?></label>
	    <div>
	        <div class="input-append">
	            <input style="width: 40px;" type="text" name="settings[system][cachetime]" value="<?= $settings->cachetime; ?>" /><span class="add-on"><?= @text( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= @text( 'TIPCACHETIME' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= @text( 'Session' ); ?></legend>
	<div>
	    <label for=""><?= @text( 'Session Lifetime' ); ?></label>
	    <div>
	        <div class="input-append">
	            <input style="width: 40px;" type="text" name="settings[system][lifetime]" value="<?= $settings->lifetime; ?>" /><span class="add-on"><?= @text( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= @text( 'TIPAUTOLOGOUTTIMEOF' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= @text( 'Locale' ); ?></legend>
	<div>
	    <label for=""><?= @text( 'Time Zone' ); ?></label>
	    <div>
	        <?= @helper('listbox.timezones', array('name' => 'settings[system][timezone]', 'selected' => $settings->timezone, 'deselect' => false, 'attribs' => array('class' => 'select-timezone', 'style' => 'width: 200px'))) ?>
	        <p class="help-block"><?= @text( 'TIPDATETIMEDISPLAY' ) .': '. @helper('date.format', array('format' => @text('DATE_FORMAT_LC2'))) ?></p>
	    </div>
	</div>
</fieldset>

<script data-inline> $jQuery(".select-timezone").select2(); </script>