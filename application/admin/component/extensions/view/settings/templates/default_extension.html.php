<?
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */
?>

<? 
$params = new \JParameter( null, $settings->getPath() );
$params->loadArray($settings->toArray());
?>

<? if($params = $params->render('settings['.$settings->getName().']')) : ?>
	<fieldset>
	    <legend><?=  translate(ucfirst($settings->getName())); ?></legend>
		<?= $params; ?>
	</fieldset>
<? endif ?>