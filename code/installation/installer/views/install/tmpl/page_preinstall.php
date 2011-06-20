<?php
/**
 * @version	$Id: preinstall.html 137 2005-09-12 10:21:17Z eddieajau $
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
				<h1><?php echo JText::_('Pre-Installation check') ?></h1>
				<div class="buttons">
					<div class="button1-left"><div class="refresh"><a onclick="submitForm( adminForm, 'preinstall' );" alt="<?php echo JText::_('Check Again') ?>"><?php echo JText::_('Check Again') ?></a></div></div>
					<div class="button1-right"><div class="prev"><a onclick="submitForm( adminForm, 'lang' );" alt="<?php echo JText::_('Previous') ?>"><?php echo JText::_('Previous') ?></a></div></div>
						<div class="button1-left"><div class="next"><a onclick="submitForm( adminForm, 'dbconfig' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
				</div>
			</div>
		</div>
	</div>

	<div id="content-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h2><?php echo JText::_('preTitle') ?> Nooku <?php echo Koowa::getVersion() ?>:</h2>
				<div class="install-text grid_6">
					<?php echo JText::_('tipPreinstall') ?>
				</div>
				<div class="install-body grid_10">
					<fieldset>
						<table class="content">
							<?php foreach($this->php_options as $option) : ?>
							<tr>
								<td class="item" valign="top">
									<?php echo $option['label'] ?>
								</td>
								<td valign="top">
									<span class="<?php echo $option['state'] ?>">
										<?php echo JText::_($option['state']) ?>
									</span>
									<span class="small">
										<?php echo isset($option['notice']) ? $option['notice'] : '' ?>&nbsp;
									</span>
								</td>
							</tr>
							<?php endforeach ?>
						</table>
					</fieldset>
				</div>
			</div>
			<div class="grid_16">
				<h2><?php echo JText::_('Recommended settings') ?>:</h2>
				<div class="grid_6 install-text">
					<?php echo JText::_('tipRecomSettings') ?>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<table class="content">
							<tr>
								<td class="toggle">
									<?php echo JText::_('Directive') ?>
								</td>
								<td class="toggle">
									<?php echo JText::_('Recommended') ?>
								</td>
								<td class="toggle">
									<?php echo JText::_('Actual') ?>
								</td>
							</tr>
							<?php foreach($this->php_settings as $setting) : ?>
							<tr>
								<td class="item">
									<?php echo $setting['label'] ?>:
								</td>
								<td class="toggle">
									<?php echo JText::_($setting['setting']) ?>
								</td>
								<td>
									<span class="<?php echo $setting['state'] ?>">
									<?php echo JText::_($setting['actual']) ?>
									</span>
								<td>
							</tr>
							<?php endforeach ?>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
	</td>

	<input type="hidden" name="task" value="" />
</form>