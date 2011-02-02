<?php
/**
 * @version	$Id: finish.html 14401 2010-01-26 14:10:00Z louis $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<form action="index.php" method="post" name="adminForm" autocomplete="off">
	<div id="toolbar-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h1><?php echo JText::_('Finish') ?></h1>
				<div class="buttons">
					<div class="button1-left"><div class="site"><a onclick="window.location.href='<?php echo $this->siteUrl ?>';" alt="<?php echo JText::_('Site') ?>"><?php echo JText::_('Site') ?></a></div></div>
					<div class="button1-left"><div class="admin"><a onclick="window.location.href='<?php echo $this->adminurl ?>';" alt="<?php echo JText::_('Admin') ?>"><?php echo JText::_('Admin') ?></a></div></div>
				</div>
			</div>
		</div>
	</div>

	<div id="content-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h2><?php echo JText::_('congratulations') ?></h2>
				<div class="grid_6 install-text">
					<?php echo JText::_('finishButtons') ?>
					<?php echo JText::_('languageinfo') ?>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<table class="final-table">
							<tr>
								<td class="error" align="center">
									<?php echo JText::_('removeInstallation') ?>
								</td>
							</tr>
							<tr>
								<td align="center">
									<h3>
										<?php echo JText::_('Administration Login Details') ?>
									</h3>
								</td>
							</tr>
							<tr>
								<td align="center" class="notice">
									<?php echo JText::_('Username') ?>: <?php echo $this->adminLogin ?>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td align="center" class="notice">
									<div id="cpanel">
										<div>
											<div class="icon">
												<a href="http://community.joomla.org/translations.html" target="_blank">
												<br />
												<b><?php echo JText::_('languagebuttonlineone') ?></b>
												<br />
												<?php echo JText::_('languagebuttonlinetwo') ?>
												<br /><br />
												</a></td>
											</div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>&nbsp;</td>
							</tr>
							<?php if(isset($this->buffer)) : ?>
							<tr>
								<td class="small">
									<?php echo JText::_('confProblem') ?>
								</td>
							</tr>
							<tr>
								<td align="center">
									<textarea rows="5" cols="60" name="configcode" onclick="this.form.configcode.focus();this.form.configcode.select();" ><?php echo $this->buffer ?></textarea>
								</td>
							</tr>
							<?php endif ?>
						</table>
					</fieldset>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" name="task" value="" />
</form>