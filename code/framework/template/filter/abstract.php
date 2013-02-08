<?php
/**
 * @version     $Id: abstract.php 1919 2010-04-25 20:49:47Z johanjanssens $
 * @package     Koowa_Template
 * @subpackage  Filter
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Abstract Template Filter
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Template
 * @subpackage  Filter
 */
abstract class KTemplateFilterAbstract extends KObject implements KTemplateFilterInterface
{
    /**
     * The filter priority
     *
     * @var integer
     */
    protected $_priority;

    /**
     * Template object
     *
     * @var object
     */
    protected $_template;

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        /*if (is_null($config->template))
        {
            throw new InvalidArgumentException(
                'template [KTemplateInterface] config option is required'
            );
        }

        if(!$config->template instanceof KTemplateInterface)
        {
            throw new UnexpectedValueException(
                'Template: '.get_class($config->template).' does not implement KTemplateInterface'
            );
        }*/

        $this->_priority = $config->priority;
        $this->_template = $config->template;
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options
     * @return void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'template' => null,
            'priority' => KCommand::PRIORITY_NORMAL,
        ));

        parent::_initialize($config);
    }

    /**
     * Get the priority of a behavior
     *
     * @return  integer The command priority
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Get the template object
     *
     * @return  object    The template object
     */
    public function getTemplate()
    {
        return $this->_template;
    }

    /**
     * Command handler
     *
     * @param   string      The command name
     * @param   object      The command context
     * @return  boolean     Always returns TRUE
     */
    final public function execute($name, KCommandContext $context)
    {
        //Set the data
        $data = $context->data;

        if (($name & KTemplateFilter::MODE_READ) && $this instanceof KTemplateFilterRead) {
            $this->read($data);
        }

        if (($name & KTemplateFilter::MODE_WRITE) && $this instanceof KTemplateFilterWrite) {
            $this->write($data);
        }

        //Get the data
        $context->data = $data;

        //@TODO : Allows filters to return false and halt the filter chain
        return true;
    }

    /**
     * Method to extract key/value pairs out of a string with xml style attributes
     *
     * @param   string  $string String containing xml style attributes
     * @return  array   Key/Value pairs for the attributes
     */
    protected function _parseAttributes($string)
    {
        $result = array();

        if (!empty($string)) {
            $attr = array();

            preg_match_all('/([\w:-]+)[\s]?=[\s]?"([^"]*)"/i', $string, $attr);

            if (is_array($attr)) {
                $numPairs = count($attr[1]);
                for ($i = 0; $i < $numPairs; $i++) {
                    $result[$attr[1][$i]] = $attr[2][$i];
                }
            }
        }

        return $result;
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

        if ($array instanceof KConfig) {
            $array = KConfig::unbox($array);
        }

        if (is_array($array)) {
            foreach ($array as $key => $item) {
                if (is_array($item)) {
                    $item = implode(' ', $item);
                }

                $output[] = $key . '="' . str_replace('"', '&quot;', $item) . '"';
            }
        }

        return implode(' ', $output);
    }
}