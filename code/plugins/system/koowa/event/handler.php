<?php
/**
 * @version     $Id$
 * @category	Koowa
 * @package     Koowa_Event
 * @copyright   Copyright (C) 2007 - 2009 Johan Janssens and Mathias Verraes. All rights reserved.
 * @license     GNU GPLv2 <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @link        http://www.koowa.org
 */

/**
 * Class to handle events.
 *
 * @author 		Johan Janssens <johan@koowa.org>
 * @category	Koowa
 * @package 	Koowa_Event
 */
class KEventHandler extends KObject implements KPatternObserver, KFactoryIdentifiable
{
	/**
	 * The object identifier
	 *
	 * @var KIdentifierInterface 
	 */
	protected $_identifier;

	/**
	 * Constructor.
	 *
	 * @param	array An optional associative array of configuration settings.
	 */
	public function __construct(array $options = array())
	{
        // Allow the identifier to be used in the initalise function
        $this->_identifier = $options['identifier'];
		
		// Initialize the options
        $options  = $this->_initialize($options);
	}

	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param	array	Options
     * @return 	array	Options
     */
    protected function _initialize(array $options)
    {
        $defaults = array(
        	'identifier'	=> null
        );

        return array_merge($defaults, $options);
    }
    
	/**
	 * Get the identifier
	 *
	 * @return 	object A KIdentifier object
	 * @see 	KFactoryIdentifiable
	 */
	public function getIdentifier()
	{
		return $this->_identifier;
	}
	
	/**
	 * Method to trigger events
	 *
	 * @param  object	The event arguments
	 * @return mixed Routine return value
	 */
	public function update(ArrayObject $args)
	{		
		if (method_exists($this, $args['event'])) {
			return $this->{$args['event']}($args);
		} 
		
		return null;
	}
}