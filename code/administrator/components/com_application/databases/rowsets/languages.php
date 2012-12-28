<?php
/**
 * @version     $Id: components.php 5058 2012-08-31 09:30:32Z gergoerdosi $
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Languages Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationDatabaseRowsetLanguages extends KDatabaseRowsetAbstract implements KServiceInstantiatable
{
    protected $_active;
    protected $_primary;

    public function __construct(KConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $components = $this->getService('com://admin/languages.model.languages')
            ->enabled(true)
            ->application('site')
            ->getRowset();

        $this->merge($components);
    }

    protected function _initialize(KConfig $config)
    {
        //Force set the identity column
        $config->identity_column = 'id';

        parent::_initialize($config);
    }

    public static function getInstance(KConfigInterface $config, KServiceInterface $container)
    {
        if (!$container->has($config->service_identifier))
        {
            //Create the singleton
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $container->set($config->service_identifier, $instance);
        }

        return $container->get($config->service_identifier);
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