<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

<meta http-equiv="X-UA-Compatible" content="chrome=1">

<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />

<?php if($this->params->get('flexBox', '1')) : ?>
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo  $this->template ?>/js/chromatable.js"></script>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo  $this->template ?>/css/default.css" rel="stylesheet" type="text/css" />
<?php else : ?>
<link href="<?php echo $this->baseurl ?>/templates/<?php echo  $this->template ?>/css/legacy.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo  $this->template ?>/js/index.js"></script>

</head>
<body id="minwidth-body" class="<?php echo JRequest::getVar('option', 'cmd'); ?>">
	<div id="container">
		<div id="header-box">
			<jdoc:include type="modules" name="menu" />
			<jdoc:include type="modules" name="status"  />
		</div>
		<div id="tabs-box">
			<jdoc:include type="modules" name="submenu" />
		</div>
		<?php //if($this->countModules('toolbar OR title')) : ?>
		<div id="toolbar-box">
			<jdoc:include type="modules" name="toolbar" />
		</div>
		<?php //endif; ?>
		<jdoc:include type="message" />
		<div id="window-body" class="<?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?>">
            <?php if(isset($this->modules['sidebar']) && count($this->modules['sidebar'])) : ?>
			<div id="window-sidebar">
				<jdoc:include type="modules" name="sidebar" />
			</div>
            <?php endif; ?>
			<div id="window-content" class="<?php echo (JRequest::getInt('hidemainmenu')) ? 'form' : 'default' ?> row-fluid">
				<jdoc:include type="component" />
			</div>
			<?php if(isset($this->modules['inspector']) && count($this->modules['inspector'])) : ?>
			<div id="window-inspector">
				<jdoc:include type="modules" name="inspector" />
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if(KDEBUG) : ?>
		<?php echo KService::get('com://admin/debug.controller.debug')->display(); ?>
	<?php endif; ?>
	
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo  $this->template ?>/js/chosen.mootools.1.2.js"></script>
	<script> $$(".chzn-select").chosen(); </script>
</body>
</html>