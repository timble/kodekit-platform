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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<link rel="stylesheet" href="templates/witblits/css/component.css" />
	<link rel="stylesheet" href="templates/witblits/css/print.css" />
	<?php if($option=='com_mailto') : ?>
	<link rel="stylesheet" href="templates/witblits/css/mailto.css" />
	<?php endif; ?>
	
</head>
<body id="witblits" class="component">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>