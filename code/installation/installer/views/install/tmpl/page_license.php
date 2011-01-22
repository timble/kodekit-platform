<?php
/**
 * @version	$Id: license.html 137 2005-09-12 10:21:17Z eddieajau $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<form action="index.php" method="post" name="adminForm">
	<div id="toolbar" class="group">
		<h1><?php echo JText::_('License') ?></h1>
		<div class="buttons">
			<div class="button1-right"><div class="prev"><a onclick="submitForm( adminForm, 'preinstall' );" alt="<?php echo JText::_('Previous') ?>"><?php echo JText::_('Previous') ?></a></div></div>
			<div class="button1-left"><div class="next"><a onclick="submitForm( adminForm, 'dbconfig' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
		</div>
	</div>

	<div id="installer" class="group">
		<h2><?php echo JText::_('GNU/GPL License') ?>:</h2>
		<iframe src="gpl.html" class="license" frameborder="0" marginwidth="25" scrolling="auto"></iframe>
	</div>

	<input type="hidden" name="task" value="" />
</form>
