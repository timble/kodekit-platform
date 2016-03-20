<?
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-deugger for the canonical source repository
 */
?>

<ktml:style src="assets://debug/css/debug-default.css" />
<ktml:script src="assets://debug/js/debug.js" />

<div id="debug" class="profiler">
<a class="close" title="<?= translate("press 'd' to bring the debug bar back up") ?>"></a>
<?=	helper('tabs.startPane', array('id' => 'debug')); ?>

<?= helper('tabs.startPanel', array('title' => 'Overview', 'attribs' => array( 'class' => 'profiles'))); ?>
    <?= import('default_overview.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Profiles', 'attribs' => array( 'class' => 'timeline'))); ?>
	<?= import('default_profiles.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Queries', 'attribs' => array( 'class' => 'storage'))); ?>
	<?= import('default_queries.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Resources', 'attribs' => array( 'class' => 'resources'))); ?>
	<?= import('default_resources.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Strings', 'attribs' => array( 'class' => 'audits'))); ?>
	<?= import('default_strings.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.endPane'); ?>
</div>