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
 * Google Chart Radar
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGoogleRadar extends KChartGoogle
{
    // ('Radar' => 'r');
    protected $_type    = 'r';

    /**
     * Constructor
     */
    public function __construct()
    {
        throw new KException(__CLASS__. ' is not implemented yet.');
    }
}