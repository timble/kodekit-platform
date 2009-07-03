<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

// Check if Koowa is active
if(!defined('KOOWA')) {
	JError::raiseWarning(0, JText::_("Koowa wasn't found. Please install the Koowa plugin and enable it."));
	return;
}

// Create the component dispatcher
$dispatcher = KFactory::get('site::com.beer.dispatcher', array('default_view' => 'people'));
$dispatcher->dispatch();