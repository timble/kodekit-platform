<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @copyright   Copyright (C) 2007 - 2009 Joomlatools. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Google Chart Bar
 *
 * @author      Mathias Verraes <mathias@koowa.org>
 * @category	Koowa
 * @package     Koowa_Chart
 * @subpackage  Google
 * @version     1.0
 */
abstract class KChartGoogleBar extends KChartGoogle
{

    public      $barWidth;
    private     $realBarWidth;
    protected   $_groupSpacerWidth = 1;
    protected   $totalBars = 1;
    protected   $isHoriz = false;

    public function getUrl()
    {
        $this->scaleValues();
        $this->setBarWidth();
        $retStr = parent::concatUrl();
        $retStr .= "&chbh=$this->realBarWidth,$this->_groupSpacerWidth";
        return $retStr;
    }

    protected function _setBarCount()
    {
        $this->totalBars = KHelperArray::count($this->_data);
    }

    protected function getAxisLabels()
    {
        $retStr = "";
        $xAxis = 0;
        if($this->isHoriz)
            $xAxis = 1;
        $yAxis = 1 - $xAxis;
        if(isset($this->_xAxisLabels)){
            $retStr = "&chxl=$xAxis:|".$this->encodeData($this->_xAxisLabels,"", "|");
        }
        return $retStr;
    }
    private function setBarWidth()
    {
        if(isset($this->barWidth)){
            $this->realBarWidth = $this->barWidth;
            return;
        }
        $this->_setBarCount();
        $totalGroups = KChartGoogleHelper::getMaxCountOfArray($this->_data);
        if($this->isHoriz)
            $chartSize = $this->_height - 50;
        else
            $chartSize = $this->_width - 50;

        $chartSize -= $totalGroups * $this->_groupSpacerWidth;
        $this->realBarWidth = round($chartSize/$this->totalBars);
    }

    /**
     * Set group spacer width
     *
     * @param	integer	width
     * @retun 	this
     */
    public function setGroupSpacerWidth($width)
    {
        $this->_groupSpacerWidth = $width;
    	return $this;
    }

}