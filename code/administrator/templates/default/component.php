<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>

<meta http-equiv="X-UA-Compatible" content="chrome=1">

<jdoc:include type="head" />

<link href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/default.css" rel="stylesheet" type="text/css" />

</head>
<body id="tmpl-component" class="<?php echo JRequest::getVar('option', 'cmd'); ?> contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>