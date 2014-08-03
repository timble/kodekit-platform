<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2007 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Library;

/**
 * Abstract Template Helper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Library\Template
 */
abstract class TemplateHelperAbstract extends Object implements TemplateHelperInterface
{
	/**
	 * Template object
	 *
	 * @var	TemplateInterface
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

        // Set the template object
        $this->setTemplate($config->template);
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options
     * @return void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'template' => null,
        ));

        parent::_initialize($config);
    }

    /**
     * Translates a string and handles parameter replacements
     *
     * @param string $string String to translate
     * @param array  $parameters An array of parameters
     * @return string Translated string
     */
    public function translate($string, array $parameters = array())
    {
        return $this->getTemplate()->translate($string, $parameters);
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
    public function buildAttributes($array)
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

                if (is_bool($item))
                {
                    if ($item === false) continue;
                    $item = $key;
                }

                $output[] = $key.'="'.str_replace('"', '&quot;', $item).'"';
            }
        }

        return implode(' ', $output);
    }
}