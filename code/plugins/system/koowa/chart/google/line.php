<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Google Chart Line
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGoogleLine extends KChartGoogle
{
    // 'Line' => 'lc', 'LineXY' => 'lxy', 'Sparkline' => 'ls');
    protected $_type    = 'lc';

    /**
     * Set style to sparkline
     *
     * return 	this
     */
    public function setSparkline()
    {
    	$this->_type = 'ls';
    	return $this;
    }

    /**
     * Set style to LineXY (lines with dots)
     *
     * return 	this
     */
    public function setLineXY()
    {
    	$this->_type = 'lxy';
    	return $this;
    }

	/**
	 * Set style to line (default)
	 *
	 * return	this
	 */
    public function setLine()
    {
    	$this->_type = 'lc';
    	return $this;
    }
}