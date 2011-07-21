<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo $this->template ?>/css/login.css" rel="stylesheet" type="text/css" />

<?php  if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo $this->template ?>/css/login_rtl.css" rel="stylesheet" type="text/css" />
<?php  endif; ?>
</head>

<?php echo JHTML::_('behavior.keepalive'); ?>

<body class="<?php echo JRequest::getVar('option', 'cmd'); ?>">
	<div id="container">
		<div id="login-box" class="login">
			<img src="templates/<?php echo $this->template ?>/images/nooku-server_logo.png" alt="Nooku Server logo">
			<jdoc:include type="message" />
			<div id="section-box">
				<jdoc:include type="component" />
			</div>
			<a class="return" href="<?php echo JURI::root(); ?>">
				<?php echo JText::_('Return to') ?>
				<?php echo $this->params->get('showSiteName') ? $mainframe->getCfg( 'sitename' ) : JText::_('Website'); ?>
			</a>
			<div class="clr"></div>
		</div>
	</div>
</body>
</html>