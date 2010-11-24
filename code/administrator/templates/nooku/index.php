<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo  $this->language; ?>" lang="<?php echo  $this->language; ?>" dir="<?php echo  $this->direction; ?>" id="minwidth" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />

<?php if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/template_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<!--[if IE 7]>
<link href="templates/<?php echo  $this->template ?>/css/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->

<!--[if lte IE 6]>
<link href="templates/<?php echo  $this->template ?>/css/ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->

<link rel="stylesheet" type="text/css" href="templates/<?php echo  $this->template ?>/css/rounded.css" />

<?php if(JModuleHelper::isEnabled('menu')) : ?>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/menu.js"></script>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/index.js"></script>
<?php endif; ?>

</head>
<body id="minwidth-body">
	<div id="container">
		<div id="border-top">
			<span class="version"><?php echo  JText::_('Version') ?> <?php echo  JVERSION; ?></span>
			<span class="title"><?php echo $this->params->get('showSiteName') ? $mainframe->getCfg( 'sitename' ) : JText::_('Administration'); ?></span>
		</div>
		<div id="header-box">
			<div id="module-status">
				<jdoc:include type="modules" name="status"  />
			</div>
			<div id="module-menu">
				<jdoc:include type="modules" name="menu" />
			</div>
			<div class="clr"></div>
		</div>
		<div id="content-box">
			<?php if (!JRequest::getInt('hidemainmenu')): ?>
			<jdoc:include type="modules" name="submenu" style="rounded" id="submenu-box" />
			<?php endif; ?>
			<div id="toolbar-box">
				<jdoc:include type="modules" name="toolbar" />
				<jdoc:include type="modules" name="title" />
				<div class="clr"></div>
			</div>
			<div class="clr"></div>
			
			<jdoc:include type="message" />
			<div id="element-box">
				<jdoc:include type="component" />
				<div class="clr"></div>
	   		</div>
			<noscript>
				<?php echo  JText::_('WARNJAVASCRIPT') ?>
			</noscript>
			<div class="clr"></div>
		</div>
	</div>
	
	<div id="footer">
		<p class="copyright">
			<a href="http://www.joomla.org" target="_blank">Joomla!</a>
			<?php echo  JText::_('ISFREESOFTWARE') ?>
		</p>
	</div>
</body>
</html>
