<?php
/**
* @version		$Id$
* @category		Koowa
* @package      Koowa
* @copyright    Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
* @license      GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @link         http://www.koowa.org
*/
defined('KOOWA') or die('Restricted access');

// Module parameters
$direction = $params->get('direction', 'ASC');

// Get ordered list of enabled boats from model
$model	= KFactory::get('admin::com.harbour.model.boats');
$boats 	= $model->order('name')->direction($direction)->getList();

// Create a view
$view	= KFactory::get('site::com.harbour.view.html');
$view->addTemplatePath(dirname(JModuleHelper::getLayoutPath('mod_harbour', 'default')));

// Assign vars and render view
$view->assign('boats', $boats);
$view->display();