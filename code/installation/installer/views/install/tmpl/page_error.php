<?php
/**
 * @version	$Id: error.html 14401 2010-01-26 14:10:00Z louis $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<form action="index.php" method="post" name="adminForm">
	<div id="toolbar-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h1><?php echo JText::_('Error') ?></h1>
				<div class="buttons">
					<div class="button1-right"><div class="prev"><a onclick="submitForm( adminForm, '{BACK}' );" alt="<?php echo JText::_('Previous') ?>"><?php echo JText::_('Previous') ?></a></div></div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="content-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h2><?php echo JText::_('An error has occurred') ?>:</h2>
				<div class="grid_6 install-text">
					<p>
						<?php echo $this->message ?>
					</p>
				</div>
				<?php if(isset($this->xmessage)) : ?>
					<div class="grid_10 install-form">
						<fieldset class="form-block">
							<textarea rows="10" cols="50"><?php echo $this->xmessage ?></textarea>
						</fieldset>
					</div>
				<?php endif ?>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
</form>