<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Application;

use Nooku\Library;

/**
 * MySQL Database Adapter
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Application
 */
class DatabaseAdapterMysql extends Library\DatabaseAdapterMysql
{
	/**
	 * Constructor
	 *
	 * Prevent creating instances of this class by making the contructor private
	 *
	 * @param 	Library\ObjectConfig $config An optional Library\ObjectConfig object with configuration options
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
     * @param 	Library\ObjectConfig $config	An optional Library\ObjectConfig object with configuration options.
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