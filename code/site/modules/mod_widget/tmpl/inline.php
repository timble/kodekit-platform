<?
/**
 * @version     $Id: inline.php 1711 2012-06-18 17:23:07Z gergoerdosi $
 * @package     Nooku_Server
 * @subpackage  Default
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

$parts   = $url->getQuery(true);
$package = substr($parts['option'], 4);
$view    = $parts['view'];

echo @service('com://site/'.$package.'.controller.'.KInflector::singularize($view), array('request' => $parts))->display();