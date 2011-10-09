<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Configurations Model Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Files
 */
class ComFilesModelConfigs extends ComDefaultModelDefault implements KServiceInstantiatable
{
	public function __construct(KConfig $config)
	{
		parent::__construct($config);

		$this->_state->insert('container', 'identifier', null);
	}
  
    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    { 
       // Check if an instance with this identifier already exists or not
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }
        
        return $container->get($config->service_identifier);
    }

	public function getItem()
	{
		if (!isset($this->_item))
		{
			$this->_item = $this->getService('com://admin/files.database.row.config');
			$container = $this->getService('com://admin/files.model.containers')->slug((string)$this->_state->container)->getItem();

			$this->_item->setData(json_decode($container->parameters, true));
			$this->_item->container = $container;
		}

		return parent::getItem();
	}
}