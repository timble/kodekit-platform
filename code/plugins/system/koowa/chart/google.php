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
 * Chart class for Google Chart
 *
 * Forked from GChartPhp {@link http://code.google.com/p/gchartphp/}
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @uses KObject
 */
abstract class KChartGoogle extends KObject
{
    /**
     * Base url for Google Chart API
     */
    const _BASEURL      = 'http://chart.apis.google.com/chart?'; 

    /**
     * Chart Type: Bar Stacked
     */
    const BAR_STACKED   = 'BarStacked';

    /**
     * Chart Type: Bar Grouped
     */
    const BAR_GROUPED   = 'BarGrouped';

    /**
     * Chart Type: Line
     */
    const LINE          = 'Line';

    /**
     * Chart Type: Radar
     */
    const RADAR         = 'Radar';

    /**
     * Chart Type: Radar
     */
    const SCATTER       = 'Scatter';

    /**
     * Chart Type: Radar
     */
    const VENN          = 'Venn';

    /**
     * Chart Type: Radar
     */
    const PIE           = 'Pie';

    /**
     * Chart Type: Radar
     */
    const METER         = 'Meter';

    /**
     * Chart Type: Maps
     */
    const MAP           = 'Map';

	/**
	 * Type of chart
	 *
	 * @var	string
	 */
	protected $_type;

    /**
     * Scalar
     *
     * @var	integer
     */
    protected   $_scalar = 1;

    /**
     * Data encoding type
     *
     * @var string
     */
    protected   $_dataEncodingType = 't';

    /**
     * Chart Data
     *
     * @var array
     */
    protected   $_data = array();

    /**
     * Scaled Values
     *
     * @var array
     */
    protected   $_scaledValues = array();

    /**
     * Value Labels
     *
     * @var	array
     */
    protected   $_valueLabels;

    /**
     * X-Axis Labels
     *
     * @var array
     */
    protected   $_xAxisLabels;

    /**
     * Data Colors
     *
     * @var array
     */
    protected   $_colors = array();

    /**
     * Width
     *
     * @var integer
     */
    protected   $_width = 200;

    /**
     * Height
     *
     * @var integer
     */
    protected   $_height = 200;

    /**
     * Title
     *
     * @var string
     */
    private     $_title;

    /**
     * Backgrounds
     *
     * @var array	Array of background objects
     */
    protected   $_backgrounds = array();

    /**
     * Constructor
     */
    public function __construct() {}

    /**
     * Returns an instance of KChartGoogle, always creating it
     *
     * @param	string	Type
     * @return	object	KChartGoogle object
     * @throws KChartException
     */
    public static function getInstance($type)
    {
        $className = 'KChartGoogle'.$type;
        if(!class_exists($className)) {
            throw new KChartException("Chart type $type doesn't exist");
        }

        return new $className;
    }

    /**
     * Set labels for each value
     *
     * @param	array	Labels
     * @return 	this
     */
    public function setValueLabels($labels)
    {
    	$this->_valueLabels = $labels;
        return $this;
    }

    public function setTitle($newTitle)
    {
        $this->title = str_replace("\r\n", '|', $newTitle);
        $this->title = str_replace(' ', '+', $this->title);
        return $this;
    }

    /**
     * Add a background
     *
     * @param	object	KChartGoogleBackground object
     * @return	this
     */
    public function addBackground(KChartGoogleBackground $gBackground)
    {
        array_push($this->backgrounds, $gBackground);
        return $this;
    }

    protected function encodeData($data, $encoding, $separator)
    {
        switch ($this->_dataEncodingType){
            case "s":
                return $this->simpleEncodeData();
            case "e":
                return $this->extendedEncodeData();
            default:{
                $retStr = $this->textEncodeData($data, $separator, "|");
                $retStr = trim($retStr, "|");
                return $retStr;
                }
        }
    }

    private function textEncodeData($data, $separator, $datasetSeparator)
    {
        $retStr = "";
        if(!is_array($data))
            return $data;
        foreach($data as $currValue){
            if(is_array($currValue))
                $retStr .= $this->textEncodeData($currValue, $separator, $datasetSeparator);
            else
                $retStr .= $currValue.$separator;
        }

        $retStr = trim($retStr, $separator);
        $retStr .= $datasetSeparator;
        return $retStr;
    }

    /**
     * Add an array of data
     *
     * @param	array	Data
     * @return 	this
     */
    public function addData($dataArray)
    {
        array_push($this->_data, $dataArray);
        return $this;
    }

    private function simpleEncodeData()
    {
        return "";
    }

    private function extendedEncodeData()
    {
        return "";
    }

    protected function prepForUrl()
    {
        $this->scaleValues();
    }
    protected function getDataSetString()
    {
        return "&chd=".$this->_dataEncodingType.":".$this->encodeData($this->_scaledValues,"" ,",");
    }
    protected function getAxesString()
    {
        $retStr = "&chxt=x,y";
        $retStr .= "&chxr=0,1,4|1,1,10";
        return $retStr;
    }

    protected function getBackgroundString()
    {
        if(!count($this->backgrounds)) {
            return '';
        }

        $retStr = "&chf=";

        foreach($this->backgrounds as $currBg){
            $retStr .= $this->textEncodeData($currBg->toArray(), ",", "|");
        }
        $retStr = trim($retStr, "|");
        return $retStr;
    }
    protected function getAxisLabels()
    {
        $retStr = "";
        if(isset($this->_xAxisLabels))
            $retStr = "&chxl=0:|".$this->encodeData($this->_xAxisLabels,"", "|");
        return $retStr;
    }
    protected function concatUrl()
    {
        $fullUrl = self::_BASEURL;
        $fullUrl .= "cht=".$this->_type;
        $fullUrl .= "&chs=".$this->_width."x".$this->_height;

        $fullUrl .= $this->getDataSetString();
        if(isset($this->_valueLabels))
            $fullUrl .= "&chdl=".$this->encodeData($this->getApplicableLabels($this->_valueLabels),"", "|");
        $fullUrl .= $this->getAxisLabels();
        $fullUrl .= "&chco=".$this->encodeData($this->_colors,"", ",");
        if(isset($this->title))
            $fullUrl .= "&chtt=".$this->title;
        $fullUrl .= $this->getAxesString();
//          $fullUrl .= $this->getBackgroundString();

        return $fullUrl;
    }
    protected function getApplicableLabels($labels)
    {
        $trimmedValueLabels = $labels;
        // fix for http://groups.google.com/group/nooku-framework/browse_thread/thread/2a02ffdc174ce9cf?hl=en#
        return array_splice($trimmedValueLabels, 0, count($this->_data[0]));
        
    }
    public function getUrl()
    {
        $this->prepForUrl();
        return $this->concatUrl();
    }

    /**
     * Scale values
     *
     * @return	this
     */
    protected function scaleValues()
    {
        $this->setScalar();
        $this->_scaledValues = KChartGoogleHelper::getScaledArray($this->_data, $this->_scalar);
        return $this;
    }

    /**
     * Set to scalar
     *
     * @return 	this
     */
    function setScalar()
    {
        $maxValue = 100;
        $maxValue = max($maxValue, KChartGoogleHelper::getMaxOfArray($this->_data));
        if($maxValue <100) {
            $this->_scalar = 1;
        }
        else {
            $this->_scalar = 100/$maxValue;
        }
        return $this;
    }

    /**
     * Set Colors
     *
     * @param	array 	Colors
     * @return 	this
     */
    public function setColors($colors)
    {
    	$this->_colors = $colors;
        return $this;
    }

    /**
     * Set width
     *
     * @param	integer	Width in pixels
     * @return 	this
     */
    public function setWidth($width)
    {
    	$this->_width = $width;
        return $this;
    }

    /**
     * Set height
     *
     * @param   integer Height in pixels
     * @return  this
     */
    public function setHeight($height)
    {
        $this->_height = $height;
        return $this;
    }
}






