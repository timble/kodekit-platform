<?php
/**
 * @version		$Id$
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 * @copyright	Copyright (C) 2010 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

/**
 * Dispatcher
 *
 * @author      Johan Janssens <johan@timble.net>
 * @category	Koowa
 * @package     Koowa_Components
 * @subpackage  Versions
 */
class ComVersionsDispatcher extends ComDefaultDispatcher
{
 	/**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional KConfig object with configuration options.
     * @return 	void
     */
    protected function _initialize(KConfig $config)
    {
    	$config->append(array(
    		'controller_default' => 'revisions'
        ));

        parent::_initialize($config);
    }
}
