<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo  $this->template ?>/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/component.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/koowa.css" rel="stylesheet" type="text/css" />

<?php if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/general_rtl.css" rel="stylesheet" type="text/css" />
	<link href="templates/<?php echo  $this->template ?>/css/component_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

<?php if(strpos(KRequest::get('server.HTTP_USER_AGENT', 'word'), 'Titanium')) : ?>
     <link href="templates/desktop/css/template.css" rel="stylesheet" type="text/css" />
 <?php endif ?>

</head>
<body id="tmpl-component" class="<?php echo JRequest::getVar('option', 'cmd'); ?> contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>