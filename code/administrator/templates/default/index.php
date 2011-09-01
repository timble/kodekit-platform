<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/template.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/960_fluid.css" rel="stylesheet" type="text/css" media="screen and (min-width:1025px)" />
<link href="templates/tablet/css/960_fluid.css" rel="stylesheet" type="text/css" media="screen and (max-width: 1024px)" />

<?php if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/template_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/chromatable.js"></script>

<?php if(JModuleHelper::isEnabled('menu')) : ?>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/index.js"></script>
<?php endif; ?>

<!--[if IE]>
<script src="http://domassistant.googlecode.com/svn/branches/2.8/DOMAssistantCompressed.js" type="text/javascript"></script>
<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/flexie.js"></script>
<link href="templates/<?php echo  $this->template ?>/css/ie.css" rel="stylesheet" type="text/css" />
 <![endif]-->
 
<?php if(strpos(KRequest::get('server.HTTP_USER_AGENT', 'word'), 'Titanium')) : ?>
     <link href="templates/desktop/css/template.css" rel="stylesheet" type="text/css" />
 <?php endif ?>

</head>
<body id="minwidth-body" class="<?php echo JRequest::getVar('option', 'cmd'); ?>">
	<div id="container" class="-koowa-box -koowa-box-vertical">
		<div id="header-box">
			<jdoc:include type="modules" name="menu" />
			<jdoc:include type="modules" name="status"  />
		</div>
		<div id="tabs-box">
			<jdoc:include type="modules" name="submenu" />
		</div>
		<?php if($this->countModules('toolbar OR title')) : ?>
		<div id="toolbar-box">
			<jdoc:include type="modules" name="toolbar" />
			<jdoc:include type="modules" name="title" />
		</div>
		<?php endif; ?>
		<jdoc:include type="message" />
		<div id="content-box" class="container_12 <?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?>">
			<jdoc:include type="component" />
		</div>
	</div>
	<?php if(KDEBUG) : ?>
		<?php echo KFactory::get('com://admin/debug.controller.debug')->display(); ?>
	<?php endif; ?>
</body>
</html>