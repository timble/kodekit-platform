<?
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://koowa/js/koowa.js" />
<style src="media://koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar.html'); ?>

<form action="" method="post" class="-koowa-form" >
<?= @helper('tabs.startPane') ?>
<h3><?= @text('Settings')?></h3>
<?= @template('default_system.html', array('settings' => $settings->system)); ?>

<h3><?= @text('Extensions')?></h3>
<? foreach($settings as $name => $setting) : ?>
	<? if($setting->getType() == 'component' && $setting->getPath()) : ?>
	    <?= @template('default_extension.html', array('settings' => $setting)); ?>
	<? endif; ?>
<? endforeach; ?>
<?= @helper('tabs.endPane') ?>
</form>