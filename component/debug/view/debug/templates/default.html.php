<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<style src="media://debug/css/debug-default.css" />
<script src="media://debug/js/debug.js" />

<div id="debug" class="profiler">
<a class="close" title="<?= translate("press 'd' to bring the debug bar back up") ?>"></a>
<?=	helper('tabs.startPane', array('id' => 'debug')); ?>

<?= helper('tabs.startPanel', array('title' => 'Overview', 'attribs' => array( 'class' => 'profiles'))); ?>
    <?= include('default_overview.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Profiles', 'attribs' => array( 'class' => 'timeline'))); ?>
	<?= include('default_profiles.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Queries', 'attribs' => array( 'class' => 'storage'))); ?>
	<?= include('default_queries.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Resources', 'attribs' => array( 'class' => 'resources'))); ?>
	<?= include('default_resources.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.startPanel', array('title' => 'Strings', 'attribs' => array( 'class' => 'audits'))); ?>
	<?= include('default_strings.html'); ?>
<?= helper('tabs.endPanel'); ?>

<?= helper('tabs.endPane'); ?>
</div>