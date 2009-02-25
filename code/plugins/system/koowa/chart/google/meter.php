<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Google Chart Meter
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGoogleMeter extends KChartGoogle
{
    // ('Meter' => 'gom');
    protected $_type    = 'gom';

    /**
     * Constructor
     * 
     * @throws KChartException
     */
    public function __construct()
    {
        throw new KChartException(__CLASS__. ' is not implemented yet.');
    }
}