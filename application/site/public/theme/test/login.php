<?php defined( '_JEXEC' ) or die( 'Restricted access' );?>
<!DOCTYPE HTML>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
<jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/crawford/css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/crawford/css/template.css" type="text/css" />
    <style type="text/css">
        body {
            padding-top: 60px;
            padding-bottom: 40px;
        }
        .sidebar-nav {
            padding: 9px 0;
        }
    </style>
    <link href="administrator/templates/default/css/login.css" rel="stylesheet" type="text/css" />


</head>

<body class="<?php echo JRequest::getVar('option', 'cmd'); ?>">
	<div id="container">
		<div id="login-box" class="login">
			<div id="section-box">
                <form action="<?= JRoute::_('index.php?option=com_users&view=user', true); ?>" method="post" name="login" id="form-login" >
                    <input type="hidden" name="action" value="login" />
                    <input type="hidden" name="_token" value="<?php echo JUtility::getToken() ?>" />

                    <p id="form-login-username">
                        <label for="modlgn_username"><?= JText::_('Email adres') ?></label>
                        <input id="modlgn_username" type="text" name="username" class="input-large" alt="username" size="18" placeholder="<?= JText::_('Email adres') ?>"/>
                    </p>
                    <p id="form-login-password">
                        <label for="modlgn_passwd"><?= JText::_('Wachtwoord') ?></label>
                        <input id="modlgn_passwd" type="password" name="password" class="input-large" size="18" alt="password" placeholder="<?= JText::_('Wachtwoord') ?>"/>
                    </p>
                    <input type="submit" name="Submit" class="btn btn-inverse" value="<?= JText::_('LOGIN') ?>" />

                </form>
			</div>

		</div>
	</div>
</body>
</html>