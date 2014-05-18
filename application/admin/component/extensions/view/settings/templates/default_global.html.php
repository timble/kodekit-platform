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
	<legend><?= translate( 'Diagnostics' ); ?></legend>
	<div>
	    <label for="settings[system][debug]"><?= translate( 'Application Profiling' ); ?></label>
	    <div>
	        <?= helper('select.booleanlist' , array('name' => 'settings[system][debug]', 'selected' => $settings->debug));?>
	        <p class="help-block"><?= translate('TIPDEBUGGINGINFO'); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][debug_lang]"><?= translate( 'Language Indicators' ); ?></label>
	    <div>
	        <?= helper('select.booleanlist' , array('name' => 'settings[system][debug_lang]', 'selected' => $settings->debug_lang));?>
	        <p class="help-block"><?= translate('TIPDEBUGLANGUAGE'); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= translate( 'Errors' ); ?></legend>
	<div>
	    <label for="settings[system][debug_mode]"><?= translate( 'Debug mode' ); ?></label>
	    <div>
	        <?= helper('listbox.error_reportings', array('name' => 'settings[system][debug_mode]', 'selected' => $settings->debug_mode)); ?>
	        <p class="help-block"><?= translate( 'TIPERRORREPORTING' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= translate( 'Cache' ); ?></legend>
	<div>
	    <label for="settings[system][caching]"><?= translate( 'Cache' ); ?></label>
	    <div>
	        <?= helper('select.booleanlist' , array('name' => 'settings[system][caching]', 'selected' => $settings->caching));?>
	        <p class="help-block"><?= translate( 'TIPCACHE' ); ?></p>
	    </div>
	</div>
	<div>
	    <label for="settings[system][cachetime]"><?= translate( 'Cache Time' ); ?></label>
	    <div>
	        <div class="input-append">
	            <input style="width: 40px;" type="text" name="settings[system][cachetime]" value="<?= $settings->cachetime; ?>" /><span class="add-on"><?= translate( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= translate( 'TIPCACHETIME' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= translate( 'Session' ); ?></legend>
	<div>
	    <label for=""><?= translate( 'Session Lifetime' ); ?></label>
	    <div>
	        <div class="input-append">
	            <input style="width: 40px;" type="text" name="settings[system][lifetime]" value="<?= $settings->lifetime; ?>" /><span class="add-on"><?= translate( 'Minutes' ); ?></span>
	        </div>
	        <p class="help-block"><?= translate( 'TIPAUTOLOGOUTTIMEOF' ); ?></p>
	    </div>
	</div>
</fieldset>

<fieldset>
	<legend><?= translate( 'Locale' ); ?></legend>
	<div>
	    <label for=""><?= translate( 'Time Zone' ); ?></label>
	    <div>
	        <?= helper('listbox.timezones', array('name' => 'settings[system][timezone]', 'selected' => $settings->timezone, 'deselect' => false, 'attribs' => array('class' => 'select-timezone', 'style' => 'width: 200px'))) ?>
	        <p class="help-block"><?= translate( 'TIPDATETIMEDISPLAY' ) .': '. helper('date.format', array('format' => translate('DATE_FORMAT_LC2'))) ?></p>
	    </div>
	</div>
</fieldset>

<script data-inline> $jQuery(".select-timezone").select2(); </script>