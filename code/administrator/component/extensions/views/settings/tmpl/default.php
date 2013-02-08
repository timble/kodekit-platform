<?
/**
 * @version     $Id$
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<!--
<script src="media://lib_koowa/js/koowa.js" />
<style src="media://lib_koowa/css/koowa.css" />
-->

<?= @template('com://admin/default.view.form.toolbar'); ?>

<form action="" method="post" class="-koowa-form" >
<?= @helper('tabs.startPane') ?>
<h3><?= @text('System')?></h3>	
<?= @template('default_system', array('settings' => $settings->system)); ?>	

<h3><?= @text('Components')?></h3>	
<? foreach($settings as $name => $setting) : ?>
	<? if($setting->getType() == 'component' && $setting->getPath()) : ?>
	    <?= @template('default_component', array('settings' => $setting)); ?>
	<? endif; ?>
<? endforeach; ?>
<?= @helper('tabs.endPane') ?>
</form>