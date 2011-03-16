<?php
/**
 * @version	$Id: mainconfig.html 14401 2010-01-26 14:10:00Z louis $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<script language="JavaScript" type="text/javascript">
<!--
	function validateForm( frm, task ) {

		var valid_site = document.formvalidator.isValid(frm, 'vars[siteName]');
		var valid_email = document.formvalidator.isValid(frm, 'vars[adminEmail]');
		var valid_password = document.formvalidator.isValid(frm, 'vars[adminPassword]');
		var confirm_password = document.formvalidator.isValid(frm, 'vars[confirmAdminPassword]');

		var siteName 				= getElementByName( frm, 'vars[siteName]' );
		var adminEmail 				= getElementByName( frm, 'vars[adminEmail]' );
		var adminPassword 			= getElementByName( frm, 'vars[adminPassword]' );
		var confirmAdminPassword 	= getElementByName( frm, 'vars[confirmAdminPassword]' );

		if (siteName.value == '' || !valid_site) {
			alert( '<?php echo JText::_('warnSiteName') ?>' );
		} else if (this.document.filename.migstatus.value == '1' && this.document.filename.dataLoaded.value == '1') {
			submitForm( frm, task ); // Migration doesn't need email or admin passord
		} else if (adminEmail.value == '' || !valid_email) {
			alert( '<?php echo JText::_('warnEmailAddress') ?>' );
		} else if (adminPassword.value == '' || !valid_password) {
			alert( '<?php echo JText::_('warnAdminPassword') ?>' );
		} else if (adminPassword.value != confirmAdminPassword.value || !confirm_password) {
			alert( '<?php echo JText::_('warnAdminPasswordDoesntMatch') ?>' );
		} else {
			if (this.document.filename.dataLoaded.value == '1' || confirm( '<?php echo JText::_('warnNoData') ?>' )) {
				submitForm( frm, task );
			} else {
				return;
			}
		}
	}

	function JDefault() {
		this.document.filename.dataLoaded.value = '1';
		xajax_instDefault(xajax.getFormValues('filename'));
	}

	function clearPasswordFields(frm) {
		var adminPassword 			= getElementByName( frm, 'vars[adminPassword]' );
		var confirmAdminPassword 	= getElementByName( frm, 'vars[confirmAdminPassword]' );

		if( adminPassword.defaultValue == adminPassword.value || confirmAdminPassword.defaultValue == confirmAdminPassword.value ) {
			adminPassword.value 		= '';
			confirmAdminPassword.value 	= '';
		}
		return;
	}
//-->
</script>

<div id="toolbar-box">
	<div class="container_16 clearfix">
		<div class="grid_16">
			<h1><?php echo JText::_('Main Configuration') ?></h1>
			<div class="buttons">
				<div class="button1-right"><div class="prev"><a onclick="submitForm( adminForm, 'ftpconfig' );" alt="<?php echo JText::_('Previous') ?>"><?php echo JText::_('Previous') ?></a></div></div>
				<div class="button1-left"><div class="next"><a onclick="validateForm( adminForm, 'saveconfig' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
			</div>
		</div>
	</div>
</div>

<div id="content-box">
	<div class="container_16 clearfix">
		<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
			<div class="grid_16">
				<h2><?php echo JText::_('Site Name') ?>:</h2>
				<div class="grid_6 install-text">
					<?php echo JText::_('enterSiteName') ?>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<table class="content2">
							<tr>
								<td class="item">
									<label for="siteName">
										<span id="sitenamemsg"><?php echo JText::_('Site name') ?></span>
									</label>
								</td>
								<td align="center">
								<input class="inputbox validate required sitename sitenamemsg" type="text" id="siteName" name="vars[siteName]" size="30" value="<?php echo isset($this->siteName) ? $this->siteName : '' ?>" />
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
			<div class="grid_16">
				<h2><?php echo JText::_('confTitle') ?></h2>
				<div class="grid_6 install-text">
					<?php echo JText::_('tipConfSteps') ?>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<table class="content2">
							<tr>
								<td class="item">
								<label for="adminEmail">
									<span id="emailmsg"><?php echo JText::_('Your E-mail') ?></span>
								</label>
								</td>
								<td align="center">
								<input class="inputbox validate required email emailmsg" type="text" id="adminEmail" name="vars[adminEmail]" value="" size="30" />
								</td>
							</tr>
							<tr>
								<td class="item">
								<label for="adminPassword">
									<span id="passwordmsg"><?php echo JText::_('Admin password') ?></span>
								</label>
								</td>
								<td align="center">
								<input onfocus="clearPasswordFields( adminForm );" class="inputbox validate required password passwordmsg" type="password" id="adminPassword" name="vars[adminPassword]" value="" size="30"/>
								</td>
							</tr>
							<tr>
								<td class="item">
								<label for="confirmAdminPassword">
									<span id="confirmpasswordmsg"><?php echo JText::_('Confirm admin password') ?></span>
								</label>
								</td>
								<td align="center">
								<input class="inputbox validate required confirmpassword confirmpasswordmsg" type="password" id="confirmAdminPassword" name="vars[confirmAdminPassword]" value="" size="30"/>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
			</div>
			<input type="hidden" name="task" value="" />
		</form>
		
		<form enctype="multipart/form-data" action="index.php" method="post" name="filename" id="filename">
			<div class="grid_16">
				<h2><?php echo JText::_('loadSample') ?></h2>
				<div class="grid_6 install-text">
					<p><?php echo JText::_('LOADSQLINSTRUCTIONS1') ?></p>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<table class="content2">
							<tr>
								<td width="25%"></td>
								<td width="70%"></td>
							</tr>
							<tr>
								<td>
									<span id="theDefault"><input class="button" type="button" name="instDefault" value="<?php echo JText::_('clickToInstallDefault') ?>" onclick="JDefault();"/></span>
								</td>
								<td>
									<em>
										<?php echo JText::_('tipInstallDefault') ?>
									</em>
								</td>
							</tr>
						</table>
					</fieldset>
				</div>
				<input type="hidden" name="task" value="mainconfig" />
				<input type="hidden" name="sqlupload" value="0" />
				<input type="hidden" name="migrationupload" value="0" />
				<input type="hidden" name="loadchecked" value="<?php echo isset($this->loadchecked) ? $this->loadchecked : '' ?>" />
				<input type="hidden" name="dataLoaded" value="<?php echo isset($this->dataLoaded) ? $this->dataLoaded : '' ?>" />
				<input type="hidden" name="DBhostname" value="<?php echo isset($this->DBhostname) ? $this->DBhostname : '' ?>" />
				<input type="hidden" name="DBuserName" value="<?php echo isset($this->DBuserName) ? $this->DBuserName : '' ?>" />
				<input type="hidden" name="DBpassword" value="<?php echo isset($this->DBpassword) ? $this->DBpassword : '' ?>" />
				<input type="hidden" name="DBname" value="<?php echo isset($this->DBname) ? $this->DBname : '' ?>" />
				<input type="hidden" name="DBPrefix" value="<?php echo isset($this->DBPrefix) ? $this->DBPrefix : '' ?>" />
				<input type="hidden" name="ftpRoot" value="<?php echo isset($this->ftpRoot) ? $this->ftpRoot : '' ?>" />
				<input type="hidden" name="ftpEnable" value="<?php echo isset($this->ftpEnable) ? $this->ftpEnable : '' ?>" />
				<input type="hidden" name="ftpHost" value="<?php echo isset($this->ftpHost) ? $this->ftpHost : '' ?>" />
				<input type="hidden" name="ftpPort" value="<?php echo isset($this->ftpPort) ? $this->ftpPort : '' ?>" />
				<input type="hidden" name="ftpUser" value="<?php echo isset($this->ftpUser) ? $this->ftpUser : '' ?>" />
				<input type="hidden" name="ftpPassword" value="<?php echo isset($this->ftpPassword) ? $this->ftpPassword : '' ?>" />
				<input type="hidden" name="lang" value="<?php echo isset($this->lang) ? $this->lang : '' ?>" />
				<input type="hidden" name="migstatus" value="<?php echo isset($this->migstatus) ? $this->migstatus : '' ?>" />
			</div>
		</form>
			<div class="clr"></div>
		</div>
	</div>
</div>