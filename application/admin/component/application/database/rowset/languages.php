<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Languages Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDatabaseRowsetLanguages extends Library\DatabaseRowsetAbstract implements Library\ServiceInstantiatable
{
    protected $_active;
    protected $_primary;

    public function __construct(Library\Config $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $components = $this->getService('com:languages.model.languages')
            ->enabled(true)
            ->application('site')
            ->getRowset();

        $this->merge($components);
    }

    protected function _initialize(Library\Config $config)
    {
        $config->identity_column = 'id';
        parent::_initialize($config);
    }

    public static function getInstance(Library\Config $config, Library\ServiceManagerInterface $manager)
    {
        if (!$manager->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    public function setActive($active)
    {
        if(is_numeric($active)) {
            $this->_active = $this->find($active);
        } else {
            $this->_active = $active;
        }

        return $this;
    }

    public function getActive()
    {
        return $this->_active;
    }

    public function getPrimary()
    {
        if(!isset($this->_primary)) {
            $this->_primary = $this->find(array('primary' => 1))->top();
        }

        return $this->_primary;
    }
}