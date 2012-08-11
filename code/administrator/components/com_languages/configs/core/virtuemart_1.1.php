<?php /* $Id: virtuemart_1.1.php 923 2009-03-03 17:54:50Z johan $ */
defined('_JEXEC') or die('Restricted access');

$this->addLinks(array(
	'vm_product'    => 'index.php?option=com_virtuemart&page=product.product_form&product_id={ID}',
	'vm_category'   => 'index.php?option=com_virtuemart&page=product.product_category_form&category_id={ID}'
));