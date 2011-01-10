<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo $this->template ?>/css/login.css" rel="stylesheet" type="text/css" />

<?php  if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo $this->template ?>/css/login_rtl.css" rel="stylesheet" type="text/css" />
<?php  endif; ?>

<!--[if IE 7]>
<link href="templates/<?php echo  $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lte IE 6]>
<link href="templates/<?php echo  $this->template ?>/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="templates/<?php echo $this->template ?>/css/rounded.css" />

<script language="javascript" type="text/javascript">
	function setFocus() {
		document.login.username.select();
		document.login.username.focus();
	}
</script>
</head>
<body onload="javascript:setFocus()">
	<div id="container">
		<div id="content-box">
			<div id="element-box" class="login">
				<img src="templates/<?php echo $this->template ?>/images/nooku-server_logo.png" alt="Nooku Server logo">
				<jdoc:include type="message" />
				<jdoc:include type="component" />
				<a class="return" href="<?php echo JURI::root(); ?>">
					<?php echo JText::_('Return to') ?>
					<?php echo $this->params->get('showSiteName') ? $mainframe->getCfg( 'sitename' ) : JText::_('Website'); ?>
				</a>
				<div class="clr"></div>
			</div>
			<div class="clr"></div>
		</div>
	</div>
</body>
</html>