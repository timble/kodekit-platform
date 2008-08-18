<?php
/**
 * @version     $Id:object.php 46 2008-03-01 18:39:32Z mjaz $
 * @package     Koowa
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/*
 * Mixin example
 */

class MyClass
{
    public function myMethod()
    {
    	echo __METHOD__.'<br />';
    }
}

class OtherClass extends MyClass {
    public function myMethod()
    {
        echo __METHOD__.'<br />';
    }

    public function otherMethod()
    {
        echo __METHOD__.'<br />';
    }
}

class MyMixin extends KObject
{
    public function __construct()
    {
        $this->mixin(new MyClass);
        echo __METHOD__.'<br />';
    }
    public function otherMethod()
    {
        echo __METHOD__.'<br />';
    }
}


$obj = new MyMixin;
$obj->myMethod(); // myMethod was mixed in from MyClass
$obj->mixin(new OtherClass);
$obj->myMethod(); // myMethod is overridden by OtherClass
$obj->otherMethod(); // original classes' methods always take precedence
$obj->nonExistantMethod(); // triggers an error


/*
 * Output
 *
 * MyMixin::__construct
 * MyClass::myMethod
 * OtherClass::myMethod
 * MyMixin::otherMethod
 * Fatal error: Call to undefined method MyMixin::otherMethodWRONG()
 */