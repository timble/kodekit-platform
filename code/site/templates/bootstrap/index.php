<?php
/**
 * @version		$Id: weblinks.php 3314 2012-02-10 02:14:52Z johanjanssens $
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Weblinks
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */
 
/**
 * Template entry point
 *
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category 	Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 */
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/bootstrap.css" type="text/css" />

</head>
<body>

<header class="navbar navbar-fixed-top">
	<div class="navbar-inner container">
		<nav>
			<jdoc:include type="modules" name="user3" />
		</nav>
	</div>
</header>

<div style="padding-top: 60px;" class="container">	
	<div class="row">
		<aside class="sidebar span3">
			<jdoc:include type="modules" name="left" style="xhtml" />
		</aside>
		<div class="span9">
			<jdoc:include type="modules" name="breadcrumb" />
			<section>
				<jdoc:include type="component" />
			</section>
		</div>
	</div>
</div>
<jdoc:include type="modules" name="debug" />

</body>
</html>
