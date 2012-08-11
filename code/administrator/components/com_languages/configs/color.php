<?php
/**
 * @version     $Id: color.php 1121 2010-05-26 16:53:49Z johan $
 * @category    Nooku
 * @package     Nooku_Administrator
 * @subpackage  Config
 * @copyright   Copyright (C) 2007 - 2010 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license		GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link     	http://www.nooku.org
 */

/**
 * Simple cycle to work with html colors
 *
 * @author      Mathias Verraes <mathias@joomlatools.org>
 * @category    Nooku
 * @package     Administrator
 * @subpackage  Config
 */
class NookuConfigColor extends KObject
{
    /**
     * @var array
     */
    protected $_colors;

    /**
     * @var string
     */
    protected $_prefix = '#';

    /**
     * Constructor
     *
     * @param array Colors
     */
    public function __construct($colors = false)
    {
        if(is_array($colors)) {
            $this->_colors = $colors;
        } else {
        	$this->loadDefault();
        }
    }

    /**
     * Load a default set of colors
     */
    public function loadDefault()
    {
        $this->_colors = array(
            'red'       =>'ED2E38',
            'blue'      =>'578AD6',
            'yellow'    =>'EBAD14',
            'green'     =>'61BF1A',
            'orange'    =>'ff8833',
            'darkblue'  =>'2C63B5',
            'yellow2'   =>'F3CE72',
            'darkred'   =>'BC1019',
            'lightgreen'=>'A5EB6F',
            'darkorange'=>'E66000',
            );
        return $this;
    }

    public function set($name, $color)
    {
        if($color)
        {
            $this->_colors[strtolower($name)] = str_replace('#', '', $color);
            $this->reset();
        }
        else
        {
        	unset($this->_colors[strtolower($name)]);
        }
        return $this;

    }

    public function get($name, $default = 'FF0000')
    {
    	return $this->_prefix . (isset($this->_colors[$name]) ? $this->_colors[$name] : $default);
    }

    public function getSet()
    {
    	return $this->_colors;
    }

    public function current()
    {
      return $this->_prefix .current($this->_colors);
    }

    public function next()
    {
        if(next($this->_colors) === false)
        {
            $this->reset();
        }
        return $this->current();
    }

    public function reset()
    {
    	reset($this->_colors);
        return $this;
    }

    public function setPrefix($prefix)
    {
    	$this->_prefix = $prefix;
        return $this;
    }

}