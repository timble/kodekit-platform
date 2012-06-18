<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Modules
 * @subpackage  Widget
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */
defined('KOOWA') or die( 'Restricted access' ); 

$parts   = $url->getQuery(true);
$package = substr($parts['option'], 4);
$view    = $parts['view'];

echo @service('com://site/'.$package.'.controller.'.KInflector::singularize($view), array('request' => $parts))->display();