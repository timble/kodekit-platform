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
 * Extensions Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDatabaseRowsetExtensions extends Library\DatabaseRowsetAbstract implements Library\ObjectMultiton
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