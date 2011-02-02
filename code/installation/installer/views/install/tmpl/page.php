<?php
/**
 * @version	$Id: page.html 137 2005-09-12 10:21:17Z eddieajau $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<div id="warning">
	<noscript>
		<div id="javascript-warning">
			<?php echo JText::_('NOJAVASCRIPTWARNING') ?>
		</div>
	</noscript>
</div>

<div id="step-box">
	<div class="container_16 clearfix">
		<div class="grid_16">
			<ul id="stepbar" class="group">
				<li class="step-<?php echo $this->steps['lang'] ?>">
					1 : <?php echo JText::_('Language') ?>
				</li>
				<li class="step-<?php echo $this->steps['preinstall'] ?>">
					2 : <?php echo JText::_('Pre-Installation check') ?>
				</li>
				<li class="step-<?php echo $this->steps['dbconfig'] ?>">
					3 : <?php echo JText::_('Database') ?>
				</li>
				<li class="step-<?php echo $this->steps['ftpconfig'] ?>">
					4 : <?php echo JText::_('FTP Configuration') ?>
				</li>
				<li class="step-<?php echo $this->steps['mainconfig'] ?>">
					5 : <?php echo JText::_('Configuration') ?>
				</li>
				<li class="step-<?php echo $this->steps['finish'] ?>">
					6 : <?php echo JText::_('Finish') ?>
				</li>
			</ul>
		</div>
	</div>
</div>

<?php echo $this->loadTemplate($this->page) ?>