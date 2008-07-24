<?php
/**
 * @version     $Id: line.php 176 2008-03-30 19:46:02Z mjaz $
 * @package     Koowa_Chart
 * @subpackage  Google
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Google Chart Maps
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGoogleMap extends KChartGoogle
{
    /**
     * Map code for Africa
     */
    const AFRICA        = 'africa';

    /**
     * Map code for Asia
     */
    const ASIA          = 'asia';

    /**
     * Map code for Europe
     */
    const EUROPE        = 'europe';

    /**
     * Map code for Middle East
     */
    const MIDDLE_EAST   = 'middle_east';

    /**
     * Map code for South America
     */
    const SOUTH_AMERICA = 'south_america';

    /**
     * Map code for USA
     */
    const USA           = 'usa';

    /**
     * Map code for World
     */
    const WORLD         = 'world';




    //'Map' => 't');
    protected $_type    = 't';

    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Set geographical region
     *
     * @param	string KChartGoogleMap::
     * AFRICA|ASIA|EUROPE|MIDDLE_EAST|SOUTH_AMERICA|USA|WORLD
     * @return 	this
     */
    public function setRegion($region)
    {
    	$this->_region = strtolower($region);
        return $this;
    }

    /**
     * Concat URL
     */
     protected function concatUrl()
     {
     	$result = parent::concatUrl()
                . '&chtm='.$this->_region;
        return $result;

     }
}