<?php
/**
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Framework;

/**
 * Template Helper Class
 *
 * @author		Johan Janssens <johan@nooku.org>
 * @package		Koowa_Template
 * @subpackage	Helper
 */
abstract class TemplateHelperAbstract extends Object implements TemplateHelperInterface
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
	 * @param 	object 	An optional Config object with configuration options
	 */
	public function __construct(Config $config)
	{
		parent::__construct($config);

        /*if (is_null($config->template))
        {
            throw new \InvalidArgumentException(
                'template [TemplateInterface] config option is required'
            );
        }

        if(!$config->template instanceof TemplateInterface)
        {
            throw new \UnexpectedValueException(
                'Template: '.get_class($config->template).' does not implement TemplateInterface'
            );
        }*/

		// Set the template object
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

        if($array instanceof Config) {
            $array = Config::unbox($array);
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