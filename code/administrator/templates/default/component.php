<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />

<link href="templates/<?php echo  $this->template ?>/css/general.css" rel="stylesheet" type="text/css" />
<link href="templates/<?php echo  $this->template ?>/css/component.css" rel="stylesheet" type="text/css" />

<?php if($this->direction == 'rtl') : ?>
	<link href="templates/<?php echo  $this->template ?>/css/general_rtl.css" rel="stylesheet" type="text/css" />
	<link href="templates/<?php echo  $this->template ?>/css/component_rtl.css" rel="stylesheet" type="text/css" />
<?php endif; ?>

</head>
<body class="<?php echo JRequest::getVar('option', 'cmd'); ?>" class="contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>