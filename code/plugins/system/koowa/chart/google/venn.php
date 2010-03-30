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
 * Google Chart Venn Diagram
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
class KChartGoogleVenn extends KChartGoogle
{

    // ('Venn' => 'v');
    protected $_type    = 'v';

    private $_intersections = array(0,0,0,0);

    /**
     * Set Intersections
     *
     * @param	mixed	Intersections
     * @return	this
     */
    public function setIntersections($mixed)
    {
        $this->_intersections = $mixed;
        return $this;
    }

    protected function getAxesString()
    {
        return "";
    }

    public function getUrl()
    {
        $retStr = parent::getUrl();
//          $retStr .= "&chl=".$this->encodeData($this->_valueLabels,"", "|");
        return $retStr;
    }

    protected function getDataSetString()
    {
        $fullDataSet = array_splice($this->_scaledValues[0], 0, 3);
        while(count($fullDataSet)<3){
            array_push($fullDataSet, 0);
        }

        $scaledIntersections = KChartGoogleHelper::getScaledArray($this->_intersections, $this->_scalar);
        foreach($scaledIntersections as $temp){
            array_push($fullDataSet, $temp);
        }
        $fullDataSet = array_splice($fullDataSet, 0, 7);
        while(count($fullDataSet)<7){
            array_push($fullDataSet, 0);
        }

        return "&chd=".$this->_dataEncodingType.":".$this->encodeData($fullDataSet,"" ,",");
    }
}
