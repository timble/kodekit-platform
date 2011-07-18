<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); ?>

<?= @helper('tabs.startPanel', array('id' => 'system', 'title' => @text('General'))) ?>
<div class="grid_6">
    <?= @template('default_path'); ?>
    <?= @template('default_server'); ?>
    <?= @template('default_debug'); ?> 
    <?= @template('default_cache'); ?> 
    <?= @template('default_session'); ?>
    <?= @template('default_locale'); ?> 
</div> 
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'site', 'title' => @text('Frontend'))) ?>
	<div class="grid_6">
	    <?= @template('default_site'); ?>
	    <?= @template('default_seo'); ?>
	</div>
<?= @helper('tabs.endPanel') ?>
	
<?= @helper('tabs.startPanel', array('id' => 'mail', 'title' => @text('Mail'))) ?>
    <div class="grid_6">
    	<?= @template('default_mail'); ?>  
    </div>
<?= @helper('tabs.endPanel') ?>
 	
<?= @helper('tabs.startPanel', array('id' => 'ftp', 'title' => @text('FTP'))) ?>
	<div class="grid_6">
		<?= @template('default_ftp'); ?> 
	</div>
 <?= @helper('tabs.endPanel') ?>
 	
 <?= @helper('tabs.startPanel', array('id' => 'database', 'title' => @text('Database'))) ?>
 	<div class="grid_6">
		<?= @template('default_database'); ?> 
	</div>
 <?= @helper('tabs.endPanel') ?>