<?php
/**
 * @version	$Id: language.html 137 2005-09-12 10:21:17Z eddieajau $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<script language="JavaScript" type="text/javascript">
	function validateForm( frm, task ) {
		submitForm( frm, task );
	}
</script>

<form action="index.php" method="post" name="adminForm">
	<div id="toolbar-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h1><?php echo JText::_('Choose Language') ?></h1>
				<div class="buttons">
					<div class="button1-left"><div class="next"><a onclick="validateForm( adminForm, 'preinstall' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
				</div>
			</div>
		</div>
	</div>
	<div id="content-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h2><?php echo JText::_('Select Language') ?></h2>
				<div class="grid_8 install-text">
					<?php echo JText::_('PICKYOURCHOICEOFLANGS') ?>
				</div>
				<div class="grid_8 install-body">
					<fieldset>
						<select name="vars[lang]" class="inputbox" size="20">
						<?php foreach($this->languages as $language) : ?>
							<option value="<?php echo $language['value'] ?>" <?php echo isset($language['selected']) ? $language['selected'] : '' ?>><?php echo $language['value'].' - '.$language['text'] ?></option>
						<?php endforeach ?>
						</select>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
</form>