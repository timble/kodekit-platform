<?php
/**
 * @version	$Id: dbconfig.html 14401 2010-01-26 14:10:00Z louis $
 * @package	Joomla
 * @subpackage	Installation
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license	GNU/GPL
 */
?>

<script language="JavaScript" type="text/javascript">
<!--
	function validateForm( frm, task ) {
		var valid = document.formvalidator.isValid(frm);
		if (valid == false) {
			return false;
		}
		
		var DBhostname = getElementByName( frm, 'vars[DBhostname]' );
		var DBname = getElementByName( frm, 'vars[DBname]' );
		var DBPrefix = getElementByName( frm, 'vars[DBPrefix]' );

		var regex=/^[a-zA-Z]+[a-zA-Z0-9_]*$/;

		if (DBhostname.value == '') {
			alert( '<?php echo JText::_('validHost') ?>' );
			return;
		} else if (DBname.value == '') {
			alert( '<?php echo JText::_('validName') ?>' );
			return;
		} else if (DBPrefix.value == '') {
			alert('<?php echo JText::_('validPrefix') ?>');
			return;
		} else if (DBname.value.length > 64) {
			alert('<?php echo JText::_('MYSQLDBNAMETOOLONG') ?>');
			return;
		} else if (DBPrefix.value.length > 15) {
			alert('<?php echo JText::_('MYSQLPREFIXTOOLONG') ?>');
			return;
		} else if (!regex.test(DBPrefix.value)) {
			alert('<?php echo JText::_('MYSQLPREFIXINVALIDCHARS') ?>');
			return;
		} else {
			submitForm( frm, task );
		}
	}

	function JProcess( action ) {

		if (document.getElementById("vars_dbhostname").value == '') {
			alert( '<?php echo JText::_('validHost') ?>' );
			return;
		} else if (document.getElementById("vars_dbusername").value == '') {
			alert( '<?php echo JText::_('validUser') ?>' );
			return;
		}
	}
//-->
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" autocomplete="off">
	<div id="toolbar-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h1><?php echo JText::_('Database Configuration') ?></h1>
				<div class="buttons">
					<div class="button1-right"><div class="prev"><a onclick="submitForm( adminForm, 'preinstall' );" alt="<?php echo JText::_('Previous') ?>"><?php echo JText::_('Previous') ?></a></div></div>
					<div class="button1-left"><div class="next"><a onclick="validateForm( adminForm, 'makedb' );" alt="<?php echo JText::_('Next') ?>"><?php echo JText::_('Next') ?></a></div></div>
				</div>
			</div>
		</div>
	</div>

	<div id="content-box">
		<div class="container_16 clearfix">
			<div class="grid_16">
				<h2><?php echo JText::_('Connection Settings') ?>:</h2>
				<div class="grid_6 install-text">
					<?php echo JText::_('tipDatabaseSteps') ?>
				</div>
				<div class="grid_10 install-body">
					<fieldset>
						<h3 class="title-smenu" title="<?php echo JText::_('Basic') ?>"><?php echo JText::_('Basic Settings') ?></h3>
						<div class="section-smenu">
							<table class="content2">
							<tr>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="vars_dbhostname">
										<span id="dbhostnamemsg"><?php echo JText::_('Host Name') ?></span>
									</label>
									<br />
									<input id="vars_dbhostname" class="inputbox validate required none dbhostnamemsg" type="text" name="vars[DBhostname]" value="<?php echo isset($this->DBhostname) ? $this->DBhostname : '' ?>" />
								</td>
								<td>
									<em>
									<?php echo JText::_('tipHost') ?>
									</em>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="vars_dbusername">
										<span id="dbusernamemsg"><?php echo JText::_('User Name') ?></span>
									</label>
									<br />
									<input id="vars_dbusername" class="inputbox validate required none dbusernamemsg" type="text" name="vars[DBuserName]" value="<?php echo isset($this->DBuserName) ? $this->DBuserName : '' ?>" />
								</td>
								<td>
									<em>
									<?php echo JText::_('tipUser') ?>
									</em>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="vars_dbpassword">
										<?php echo JText::_('Password') ?>
									</label>
									<br />
									<input id="vars_dbpassword" class="inputbox" type="password" name="vars[DBpassword]" value="<?php echo isset($this->DBpassword) ? $this->DBpassword : '' ?>" />
								</td>
								<td>
									<em>
									<?php echo JText::_('tipPassword') ?>
									</em>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="vars_dbname">
										<span id="dbnamemsg"><?php echo JText::_('Database Name') ?></span>
									</label>
									<br />
									<input id="vars_dbname" class="inputbox validate required none dbnamemsg" type="text" name="vars[DBname]" value="<?php echo isset($this->DBname) ? $this->DBname : '' ?>" />
								</td>
								<td>
									<em>
									<?php echo JText::_('tipDatabase') ?>
									</em>
								</td>
							</tr>
							</table>
							<br /><br />
						</div>
						<h3 class="title-smenu" title="<?php echo JText::_('Advanced') ?>"><?php echo JText::_('Advanced settings') ?></h3>
						<div class="section-smenu">
							<table class="content2">
							<tr>
								<td></td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<input id="vars_dbolddel" type="radio" name="vars[DBOld]" value="rm" />
								</td>
								<td>
									<label for="vars_dbolddel">
										<?php echo JText::_('Drop Existing Tables') ?>
									</label>
								</td>
								<td></td>
							</tr>
							<tr>
								<td>
									<input id="vars_dboldbackup" type="radio" name="vars[DBOld]" value="bu"  checked="checked"/>
								</td>
			
								<td>
									<label for="vars_dboldbackup">
										<?php echo JText::_('Backup Old Tables') ?>
									</label>
								</td>
			
								<td>
									<em>
									<?php echo JText::_('tipBackup') ?>
									</em>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<label for="vars_dbprefix">
										<?php echo JText::_('Table Prefix') ?>
									</label>
									<br />
									<input id="vars_dbprefix" class="inputbox" type="text" name="vars[DBPrefix]" value="<?php echo isset($this->DBPrefix) ? $this->DBPrefix : '' ?>" />
								</td>
								<td>
									<em>
									<?php echo JText::_('tipPrefix') ?>
									</em>
								</td>
							</tr>
							</table>
						</div>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
	
	<input type="hidden" id="vars_lang" name="vars[lang]" value="<?php echo $this->lang ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="vars[ftpEnable]" value="0" />
</form>