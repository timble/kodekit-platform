<?php
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/general.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/crawford/css/bootstrap.min.css" type="text/css" />
    <style type="text/css">
        body {
            padding-top: 70px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/crawford/css/bootstrap-responsive.min.css" type="text/css" />
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/crawford/css/template.css" type="text/css" />
</head>
<body>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
              <a class="brand" href="#"></a>
              <div class="nav-collapse">
                <jdoc:include type="modules" name="menu" />
                <jdoc:include type="modules" name="login" />
              </div><!--/.nav-collapse -->
            </div>
        </div>
    </div>

    <jdoc:include type="component" />

    <!-- javascript

    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->

</body>
</html>
