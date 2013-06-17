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
 * Components Database Rowset Class
 *
 * @author      Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationDatabaseRowsetComponents extends Library\DatabaseRowsetAbstract implements Library\ObjectSingleton
{
    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $components = $this->getObject('com:extensions.model.components')
            ->enabled(true)
            ->fetch();

        $this->merge($components);
    }

    public function getComponent($name)
    {
        $component = $this->find(array('name' => 'com_'.$name));
        return $component;
    }

    public function isEnabled($name)
    {
        $result = false;
        if($component = $this->find(array('name' => 'com_'.$name))) {
            $result = (bool) $component->enabled;
        }

        return $result;
    }
}