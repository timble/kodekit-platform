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

<?= @helper('tabs.startPanel', array('id' => 'system', 'title' => @text('General'))) ?>
    <?= @template('default_general'); ?>
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'site', 'title' => @text('Frontend'))) ?>
	<?= @template('default_site'); ?>
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'mail', 'title' => @text('Mail'))) ?>
   	<?= @template('default_mail'); ?>  
<?= @helper('tabs.endPanel') ?>