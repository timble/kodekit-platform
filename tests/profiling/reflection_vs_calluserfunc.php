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
 * Compares the performance of ReflectionMethod::invoke() vs call_user_func()
 *
 * @todo    Replace JProfiler with KProfiler
 *
 * @author  Mathias Verraes <mathias@joomlatools.org>
 */

jimport('joomla.error.profiler');


class TestClass
{
    protected $_param;
    protected function __construct($param)
    {
    	$this->_param = $param;
    }
	public static function getInstance($param)
    {
    	return new TestClass($param);
    }
}



// Using Reflection
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    $class    = new ReflectionClass('TestClass');
    $method   = $class->getMethod('getInstance');
    $instance = $method->invoke(null, 'Some param');
}
$p->mark('Using Reflection');
var_dump($p->getBuffer());


// Using shortened Reflection
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    $method   = new ReflectionMethod('TestClass', 'getInstance');
    $instance = $method->invoke(null, 'Some param');
}
$p->mark('Using shortened Reflection');
var_dump($p->getBuffer());



// Using call_user_func
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    $instance = call_user_func(array('TestClass', 'getInstance'), 'Some param');
}
$p->mark('Using call_user_func()');
var_dump($p->getBuffer());


