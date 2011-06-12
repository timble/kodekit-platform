<?php
/**
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<jdoc:include type="head" />
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline.css" type="text/css" />
	<?php if($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/offline_rtl.css" type="text/css" />
	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
</head>
<body>
<jdoc:include type="message" />
	<div id="frame" class="outline">
		<h1>
			<?php echo $mainframe->getCfg('sitename'); ?>
		</h1>
	<p>
		<?php echo $mainframe->getCfg('offline_message'); ?>
	</p>
	<form action="<?php echo JRoute::_('index.php?option=com_users&view=login') ?>" method="post" name="login" id="form-login">
	<fieldset class="input">
		<p id="form-login-username">
			<label for="username"><?php echo JText::_('Username') ?></label><br />
			<input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('Username') ?>" size="18" />
		</p>
		<p id="form-login-password">
			<label for="password"><?php echo JText::_('Password') ?></label><br />
			<input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('Password') ?>" id="password" />
		</p>
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	</fieldset>
	<input type="hidden" name="action" value="login" />
	<input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
	<input type="hidden" name="_token" value="<?php echo JUtility::getToken() ?>" />
	</form>
	</div>
</body>
</html>