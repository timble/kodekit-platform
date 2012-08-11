<?php /* $Id: joomla_1.5.php 923 2009-03-03 17:54:50Z johan $ */
defined('_JEXEC') or die('Restricted access');

$this->addLinks(array(
	'banner'            => 'index.php?option=com_banners&task=edit&cid[]={ID}',
	'bannerclient'      => 'index.php?option=com_banners&c=client&task=edit&cid[]={ID}',
	'content'           => 'index.php?option=com_content&task=edit&cid[]={ID}',
	'menu'              => 'index.php?option=com_menus&task=edit&cid[]={ID}',
	'newsfeeds'         => 'index.php?option=com_newsfeeds&task=edit&cid[]={ID}',
	'polls'             => 'index.php?option=com_poll&view=poll&task=edit&cid[]={ID}',
	'sections'          => 'index.php?option=com_sections&task=edit&cid[]={ID}',
	'weblinks'          => 'index.php?option=com_weblinks&view=weblink&task=edit&cid[]={ID}',
	'contact_details'   => 'index.php?option=com_contact&task=edit&cid[]={ID}',
	
	/*
	 * Turned off modules for now. Issues with translations of modules with client id of 1. Client id
	 * is taken from the request. Cannot fix.
	 */
	//'modules'			=> 'index.php?option=com_modules&task=edit&cid[]={ID}',

	/*
	 *  Turned off categories for now. Different components use the categories table (weblinks, contacts, docman, ...)
	 */
	//'categories'		=> 'index.php?option=com_categories&task=edit&cid[]={ID}',
));