<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Framework;

/**
 * Pages Database Rowset Class
 *
 * @author      Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ComApplicationDatabaseRowsetPages extends Framework\DatabaseRowsetAbstract implements Framework\ServiceInstantiatable
{
    public function __construct(Framework\Config $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $pages = $this->getService('com://admin/pages.model.pages')
            ->published(true)
            ->getRowset();

        $this->merge($pages);
    }

    protected function _initialize(Framework\Config $config)
    {
        $config->identity_column = 'id';
        parent::_initialize($config);
    }

    public static function getInstance(Framework\Config $config, Framework\ServiceManagerInterface $manager)
    {
        if(!$manager->has($config->service_identifier))
        {
            $classname = $config->service_identifier->classname;
            $instance  = new $classname($config);
            $manager->set($config->service_identifier, $instance);
        }

        return $manager->get($config->service_identifier);
    }

    public function getPage($id)
    {
        $page = $this->find($id);
        return $page;
    }
}