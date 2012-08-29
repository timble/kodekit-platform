<?php
/**
 * @version		$Id$
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_Template
 * @subpackage	Helper
 */
abstract class KTemplateHelperAbstract extends KObject implements KTemplateHelperInterface
{
	/**
	 * Template object
	 *
	 * @var	object
	 */
    protected $_template;

	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 *
	 * @param 	object 	An optional KConfig object with configuration options
	 */
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		// Set the template indentifier
    	$this->_template = $config->template;
	}

    /**
     * Get the template object
     *
     * @return  object	The template object
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Method to build a string with xml style attributes from  an array of key/value pairs
     *
     * @param   mixed   $array The array of Key/Value pairs for the attributes
     * @return  string  String containing xml style attributes
     */
    public static function _buildAttributes($array)
    {
        $output = array();

        if($array instanceof KConfig) {
            $array = KConfig::unbox($array);
        }

        if(is_array($array))
        {
            foreach($array as $key => $item)
            {
                if(is_array($item)) {
                    $item = implode(' ', $item);
                }

                $output[] = $key.'="'.str_replace('"', '&quot;', $item).'"';
            }
        }

        return implode(' ', $output);
    }
}