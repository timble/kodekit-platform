<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

<meta http-equiv="X-UA-Compatible" content="chrome=1">

<jdoc:include type="head" />

<link rel="stylesheet" href="templates/system/css/system.css" type="text/css" />

<?php if($this->params->get('flexBox', '1')) : ?>
<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/chromatable.js"></script>
<link href="templates/<?php echo  $this->template ?>/css/default.css" rel="stylesheet" type="text/css" />
<?php else : ?>
<link href="templates/<?php echo  $this->template ?>/css/legacy.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if(JModuleHelper::isEnabled('menu')) : ?>
	<script type="text/javascript" src="templates/<?php echo  $this->template ?>/js/index.js"></script>
<?php endif; ?>

</head>
<body id="minwidth-body" class="<?php echo JRequest::getVar('option', 'cmd'); ?>">
	<div id="container">
		<div id="header-box" class="row">
			<jdoc:include type="modules" name="menu" />
			<jdoc:include type="modules" name="status"  />
		</div>
		<div id="tabs-box" class="row">
			<jdoc:include type="modules" name="submenu" />
		</div>
		<?php if($this->countModules('toolbar OR title')) : ?>
		<div id="toolbar-box">
			<jdoc:include type="modules" name="toolbar" />
		</div>
		<?php endif; ?>
		<jdoc:include type="message" />
		<div id="window-body" class="<?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?>">
			<div id="window-sidebar">
				<jdoc:include type="modules" name="sidebar" />
			</div>
			<div id="window-content" class="<?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?> row-fluid">
				<jdoc:include type="component" />
			</div>
			<div id="window-inspector">
				<jdoc:include type="modules" name="inspector" />
			</div>
		</div>
	</div>
	<?php if(KDEBUG) : ?>
		<?php echo KService::get('com://admin/debug.controller.debug')->display(); ?>
	<?php endif; ?>
</body>
</html>