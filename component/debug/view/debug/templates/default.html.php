<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<style src="media://debug/css/debug-default.css" />
<script src="media://debug/js/debug.js" />

<div id="debug" class="profiler">
<a class="close" title="<?= @text("press 'd' to bring the debug bar back up") ?>"></a>
<?=	@helper('tabs.startPane', array('id' => 'debug')); ?>

<?= @helper('tabs.startPanel', array('title' => 'Overview', 'attribs' => array( 'class' => 'profiles'))); ?>
    <?= @template('default_overview.html'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Profiles', 'attribs' => array( 'class' => 'timeline'))); ?>
	<?= @template('default_profiles.html'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Queries', 'attribs' => array( 'class' => 'storage'))); ?>
	<?= @template('default_queries.html'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Resources', 'attribs' => array( 'class' => 'resources'))); ?>
	<?= @template('default_resources.html'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Strings', 'attribs' => array( 'class' => 'audits'))); ?>
	<?= @template('default_strings.html'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.endPane'); ?>
</div>