<?php
/**
 * @package		Koowa_Template
 * @subpackage	Helper
 * @copyright	Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link     	http://www.nooku.org
 */

namespace Nooku\Library;

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
	 * @param ObjectConfig $config	An optional ObjectConfig object with configuration options
	 */
	public function __construct(ObjectConfig $config)
	{
		parent::__construct($config);

        //@TODO : Remove after refactoring JElement to TemplateForm
        $this->_template = $config->template;
        // Set the template object
        //$this->setTemplate($config->template);
	}

    /**
     * Set the template object
     *
     * @return  TemplateInterface $template	The template object
     */
    public function setTemplate(TemplateInterface $template)
    {
        $this->_template = $template;
        return $this;
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

        if($array instanceof ObjectConfig) {
            $array = ObjectConfig::unbox($array);
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