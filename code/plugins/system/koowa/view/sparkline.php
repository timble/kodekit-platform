<?php
/**
 * @version     $Id$
 * @package     Koowa_View
 * @subpackage  Sparkline
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * View Sparkline Class
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_View
 * @subpackage  Sparkline
 */
class KViewSparkline extends KViewAbstract
{
    /**
     * KChartSparkline object
     *
     * @var object
     */
    protected $_chart;

    public function display($tpl = null)
    {
        $width      = JRequest::getInt('w', 80);
        $height     = JRequest::getInt('h', 20);

        return $this->getChart()->render($width, $height);
    }

    /**
     * Get the chart object
     *
     * @param	string Type of the chart [line|bar]
     * @return	Chart object
     */
    public function getChart($type = 'line')
    {
    	if(!isset($this->_chart))
        {
        	$this->_chart = KChartSparkline::getInstance($type);
        }
        return $this->_chart;
    }
}