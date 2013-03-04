<?
/**
 * @package     Nooku_Server
 * @subpackage  Extensions
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('tabs.startPanel', array('id' => 'system', 'title' => @text('Global'))) ?>
    <?= @template('default_global.html'); ?>
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'site', 'title' => @text('Site'))) ?>
	<?= @template('default_site.html'); ?>
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'mail', 'title' => @text('Mail'))) ?>
   	<?= @template('default_mail.html'); ?>
<?= @helper('tabs.endPanel') ?>