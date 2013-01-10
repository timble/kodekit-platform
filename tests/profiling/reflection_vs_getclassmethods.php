<?php
/**
 * @version     $Id$
 * @package     Koowa_Profiling
 * @subpackage  Reflection
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Compares the performance of ReflectionObject::getMethods() vs
 * get_class_methods()
 * 
 * @todo	Replace JProfiler with KProfiler
 * 
 * @author	Mathias Verraes <mathias@joomlatools.org>
 */

jimport('joomla.error.profiler');

$methods = array();
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    $r = new ReflectionObject($this);
    foreach($r->getMethods() as $method)
    {
        $methods[$method->name] = $this;
    }
}
$p->mark('Using Reflection');
var_dump($p->getBuffer());



$methods = array();
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    foreach(get_class_methods($this) as $method)
    {
        $methods[$method] = $this;
    }
}
$p->mark('Using get_class_methods()');
var_dump($p->getBuffer());
