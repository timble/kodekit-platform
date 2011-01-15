<?php
/**
 * @version	$Id: page.html 137 2005-09-12 10:21:17Z eddieajau $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<div id="stepbar">
	<div class="t">
		<div class="t">
			<div class="t"></div>
		</div>
	</div>
	<div class="m">
		<h1><?php echo JText::_('Steps') ?></h1>
		<div class="step-<?php echo $this->steps['lang'] ?>">
			1 : <?php echo JText::_('Language') ?>
		</div>
		<div class="step-<?php echo $this->steps['preinstall'] ?>">
			2 : <?php echo JText::_('Pre-Installation check') ?>
		</div>
		<div class="step-<?php echo $this->steps['license'] ?>">
			3 : <?php echo JText::_('License') ?>
		</div>
		<div class="step-<?php echo $this->steps['dbconfig'] ?>">
			4 : <?php echo JText::_('Database') ?>
		</div>
		<div class="step-<?php echo $this->steps['ftpconfig'] ?>">
			5 : <?php echo JText::_('FTP Configuration') ?>
		</div>
		<div class="step-<?php echo $this->steps['mainconfig'] ?>">
			6 : <?php echo JText::_('Configuration') ?>
		</div>
		<div class="step-<?php echo $this->steps['finish'] ?>">
			7 : <?php echo JText::_('Finish') ?>
		</div>
		<div class="box"></div>
  	</div>
	<div class="b">
		<div class="b">
			<div class="b"></div>
		</div>
	</div>
</div>
<div id="warning">
	<noscript>
		<div id="javascript-warning">
			<?php echo JText::_('NOJAVASCRIPTWARNING') ?>
		</div>
	</noscript>
</div>
<?php echo $this->loadTemplate($this->page) ?>