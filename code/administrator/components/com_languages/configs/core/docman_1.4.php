<?php /* $Id: docman_1.4.php 705 2008-09-15 22:19:20Z mathias $ */
defined('_JEXEC') or die('Restricted access');

$this->addLinks(array(
	'docman'            => 'index.php?option=com_docman&section=documents&task=edit&cid[]={ID}',
	'docman_licenses'   => 'index.php?option=com_docman&section=licenses&task=edit&cid[]={ID}'
));