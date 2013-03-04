<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */
?>

<? 
$params = new JParameter( null, $settings->getPath() );
$params->loadArray($settings->toArray());
?>

<? if($params = $params->render('settings['.$settings->getName().']')) : ?>
<?= @helper('tabs.startPanel', array('id' => $settings->getName(), 'title' => @text(ucfirst($settings->getName())))) ?>
	
	<fieldset class="form-horizontal">
	    <legend><?=  @text(ucfirst($settings->getName())); ?></legend>
		<?= $params; ?>
	</fieldset>
<?= @helper('tabs.endPanel') ?>
<? endif ?>