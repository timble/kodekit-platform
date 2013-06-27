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
class ApplicationDatabaseRowsetExtensions extends Library\DatabaseRowsetAbstract implements Library\ObjectSingleton
{
    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $extensions = $this->getObject('com:extensions.model.extensions')
            ->enabled(true)
            ->getRowset();

        $this->merge($extensions);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->identity_column = 'name';
        parent::_initialize($config);
    }

    public function getExtension($name)
    {
        $extension = $this->find('com_'.$name);
        return $extension;
    }

    public function isEnabled($name)
    {
        $result = false;
        if($extension = $this->find('com_'.$name)) {
            $result = (bool) $extension->enabled;
        }

        return $result;
    }

    public function __get($name)
    {
        return $this->getExtension($name);
    }
}