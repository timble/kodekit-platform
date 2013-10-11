<?php
/**
 * @version     $Id$
 * @package     Koowa_Profiling
 * @subpackage  OOP
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Compares the performance of generating a new object vs cloning
 *
 * @todo    Replace JProfiler with KProfiler
 *
 * @author  Mathias Verraes <mathias@joomlatools.org>
 */


jimport('joomla.error.profiler');

$config['table']    = KFactory::get('TestTableCustomers');
$prefix             = 'test';
$suffix             = 'customers';
$data               =   array( 'test_customer_id' => '2',
                        'name' => 'Bob',
                        'company' => 'BuildingCo',
                        'user_id' => '0'
                        );


// New KRow Object
$p = new JProfiler;
for($i = 0; $i < 10000; $i++)
{
    $row = KFactory::getInstance('DatabaseRow', $prefix, $suffix, $config, null);
    $row->setProperties($data);
}
$p->mark('New KRow object');
var_dump($p->getBuffer());


// Clone KRow Object
$p = new JProfiler;
$row = KFactory::getInstance('DatabaseRow', $prefix, $suffix, $config, null);
for($i = 0; $i < 10000; $i++)
{
    $row1 = clone $row;
    $row1->setProperties($data);
}
$p->mark('Clone KRow object');
var_dump($p->getBuffer());