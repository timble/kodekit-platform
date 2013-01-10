<?
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Components
 * @subpackage  Debug
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<style src="media://com_debug/css/debug-default.css" />
<script src="media://com_debug/js/debug.js" />

<div id="debug" class="profiler">
<a class="close" title="<?= @text("press 'd' to bring the debug bar back up") ?>"></a>
<?=	@helper('tabs.startPane', array('id' => 'debug')); ?>

<?= @helper('tabs.startPanel', array('title' => 'Overview', 'attribs' => array( 'class' => 'profiles'))); ?>
    <?= @template('default_overview'); ?>	
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Profiles', 'attribs' => array( 'class' => 'timeline'))); ?>
	<?= @template('default_profiles'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Queries', 'attribs' => array( 'class' => 'storage'))); ?>
	<?= @template('default_queries'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Resources', 'attribs' => array( 'class' => 'resources'))); ?>
	<?= @template('default_resources'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.startPanel', array('title' => 'Strings', 'attribs' => array( 'class' => 'audits'))); ?>
	<?= @template('default_strings'); ?>
<?= @helper('tabs.endPanel'); ?>

<?= @helper('tabs.endPane'); ?>
</div>