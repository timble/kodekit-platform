<?php
/**
 * @package        Koowa_Object
 * @subpackage     Locator
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

namespace Nooku\Library;

/**
 * Object Abstract Locator
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Object
 * @subpackage  Locator
 */
abstract class ObjectLocatorAbstract extends Object implements ObjectLocatorInterface
{
    /**
     * The locator type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * The class prefix sequence in FIFO order
     *
     * @var array
     */
    protected $_fallbacks = array();

    /**
     * Constructor.
     *
     * @param ObjectConfig $config  An optional ObjectConfig object with configuration options
     */
    public function __construct(ObjectConfig $config)
    {
        parent::__construct($config);

        $this->_fallbacks = ObjectConfig::unbox($config->fallbacks);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param  ObjectConfig $config An optional ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(ObjectConfig $config)
    {
        $config->append(array(
            'fallbacks' => array(),
        ));
    }

    /**
     * Get the type
     *
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * Get the locator fallbacks
     *
     * @return array
     */
    public function getFallbacks()
    {
        return $this->_fallbacks;
    }
}