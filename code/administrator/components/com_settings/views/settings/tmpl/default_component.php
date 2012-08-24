<?
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Settings
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
?>

<?= @helper('tabs.startPanel', array('id' => $settings->getName(), 'title' => @text(ucfirst($settings->getName())))) ?>
	<fieldset class="form-horizontal">
	    <legend><?=  @text(ucfirst($settings->getName())); ?></legend>
		<? 
		    $params = new JParameter( null, $settings->getPath() );
			$params->loadArray($settings->toArray());
			
			echo $params->render('settings['.$settings->getName().']');
		?>
	</fieldset>
<?= @helper('tabs.endPanel') ?>