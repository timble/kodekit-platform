<?php
/**
 * @version     $Id$
 * @package     Koowa_Chart
 * @subpackage  Google
 * @copyright   Copyright (C) 2007 - 2008 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Google Chart Pie
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGooglePie extends KChartGoogle
{
    // ('Pie' => 'p', 'Pie3D' => 'p3');
    protected $_type    = 'p';

    function __construct()
    {
        $this->_width = $this->_height * 2;
    }

    function setScalar()
    {
        return $this;
    }

    protected function getAxesString()
    {
        return '';
    }

    public function getUrl()
    {
        $retStr = parent::getUrl();
        $retStr .= '&chl='.$this->encodeData($this->_valueLabels,'', '|');
        return $retStr;
    }

    private function getScaledArray($unscaledArray, $scalar)
    {
        return $unscaledArray;
    }


    /**
     * Make the pie 3D
     *
     * @return this
     */
    public function set3D()
    {
        $this->_type = 'p3';
        return $this;
    }

    /**
     * Make the pie 2D (default)
     *
     * @return this
     */
    public function set2D()
    {
    	$this->_type = 'p';
        return $this;
    }
}