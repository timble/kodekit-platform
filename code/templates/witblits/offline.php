<?php
/**
 * @version		$Id$
 * @category	Nooku
 * @package		Nooku_Templates
 * @subpackage	Witblits
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require_once('templates/'.$this->template.'/lib/functions.php');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/witblits/css/offline.css" type="text/css" />
	<?php if($this->direction == 'rtl') : ?>
	<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/witblits/css/offline_rtl.css" type="text/css" />
	<?php endif; ?>
	<style type="text/css">
	<?php 
		if($color_background !== '') echo "html{background-color:$color_background;}";
		if($color_foreground !== '') echo "body{background-color:$color_foreground;}";
	?>
	</style>
</head>
<body class="<?php echo bodyClasses($menu, $view); ?> offline" id="witblits">
	<div id="wrap">
	  <div id="branding">
	    <?php if($logo_image !== "-1") : ?>
	    	<img src="<?php echo $tpath; ?>/images/logos/<?php echo $logo_image; ?>" width="<?php echo $logo_width; ?>" height="<?php echo $logo_height; ?>" alt="<?php if ( $logo_text != ""){ echo $logo_text; } else { echo $mainframe->getCfg('sitename'); } ?>" border="0" class="clearfix" />
	    <?php else : ?>
	    	<h1><?php if ( $logo_text !== ""){ echo $logo_text; } else { echo $mainframe->getCfg('sitename'); } ?></h1>
	    <?php endif; ?>
	    <?php if ( $logo_text != "") : ?>
	   	 <p class="tagline"><?php echo $tagline_text; ?></p>
	    <?php endif; ?>
	  </div>
	  <div id="block">
	    <h2><?php echo $mainframe->getCfg('offline_message'); ?></h2>
	    <jdoc:include type="message" />      
	    <form action="index.php" method="post" name="login" id="form-login">
	      <p id="form-login-username">
	        <label for="username"><?php echo JText::_('Username') ?></label><br />
	        <input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('Username') ?>" size="18" />
	      </p>
	      <p id="form-login-password">
	        <label for="passwd"><?php echo JText::_('Password') ?></label><br />
	        <input type="password" name="passwd" class="inputbox" size="18" alt="<?php echo JText::_('Password') ?>" id="passwd" />
	      </p>
	      <p id="form-login-remember">
	        <input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('Remember me') ?>" id="remember" />
	        <label for="remember"><?php echo JText::_('Remember me') ?></label>
	      </p>
	      <input type="submit" name="Submit" class="button" value="<?php echo JText::_('LOGIN') ?>" />
	      <input type="hidden" name="option" value="com_user" />
	      <input type="hidden" name="task" value="login" />
	      <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
	    <?php echo JHTML::_( 'form.token' ); ?>
	    </form>
	  </div>
	</div>
</body>
</html>