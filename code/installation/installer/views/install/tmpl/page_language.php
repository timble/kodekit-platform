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
	<div id="toolbar" class="group">
		<h1><?php echo JText::_('Choose Language') ?></h1>
		<div class="buttons">
			<div class="button1-left"><div class="next"><a onclick="validateForm( adminForm, 'preinstall' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
		</div>
	</div>
	<div id="installer" class="group">
		<h2><?php echo JText::_('Select Language') ?></h2>
		<div class="install-text">
			<?php echo JText::_('PICKYOURCHOICEOFLANGS') ?>
		</div>
		<div class="install-body">
			<fieldset>
				<select name="vars[lang]" class="inputbox" size="20">
				<?php foreach($this->languages as $language) : ?>
					<option value="<?php echo $language['value'] ?>" <?php echo isset($language['selected']) ? $language['selected'] : '' ?>><?php echo $language['value'].' - '.$language['text'] ?></option>
				<?php endforeach ?>
				</select>
			</fieldset>
		</div>
	</div>
	
	<input type="hidden" name="task" value="" />
</form>