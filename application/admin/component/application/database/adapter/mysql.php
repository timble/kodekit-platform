<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * MySQL Database Adapter
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDatabaseAdapterMysql extends Library\DatabaseAdapterMysql implements Library\ObjectMultiton
{
	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 *
	 * @param 	object 	An optional Library\ObjectConfig object with configuration options
	 */
	public function __construct(Library\ObjectConfig $config)
	{
		parent::__construct($config);

        //Auto connect to the database
        $this->connect();
	}

    /**
     * Initializes the options for the object
     *
     * Called from {@link __construct()} as a first step of object instantiation.
     *
     * @param 	object 	An optional Library\ObjectConfig object with configuration options.
     * @return  void
     */
    protected function _initialize(Library\ObjectConfig $config)
    {
        $application = $this->getObject('application');

        $config->append(array(
            'options'	=> array(
                'host'		   => $application->getCfg('host'),
                'username'	   => $application->getCfg('user'),
                'password'     => $application->getCfg('password'),
                'database'	   => $application->getCfg('db'),
            )
        ));

        parent::_initialize($config);
    }
}