<?php
/**
 * @version     $Id$
 * @package        Koowa_Service
 * @subpackage     Locator
 * @copyright    Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 */

/**
 * Service Abstract Locator
 *
 * @author        Johan Janssens <johan@nooku.org>
 * @package     Koowa_Service
 * @subpackage     Locator
 */
abstract class KServiceLocatorAbstract extends KObject implements KServiceLocatorInterface
{
    /**
     * The type
     *
     * @var string
     */
    protected $_type = '';

    /**
     * The class prefix sequence in FIFO order
     *
     * @var array
     */
    protected $_prefixes = array();

    /**
     * Constructor.
     *
     * @param   object  An optional KConfig object with configuration options
     */
    public function __construct(KConfig $config)
    {
        parent::__construct($config);

        $this->_prefixes = KConfig::unbox($config->prefixes);
    }

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param   object  An optional KConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(KConfig $config)
    {
        $config->append(array(
            'prefixes' => array(),
        ));
    }

    /**
     * Get the type
     *
     * @return string    Returns the type
     */
    public function getType()
    {
        return $this->_type;
    }
}