<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Pages;

/**
 * Pages Database Rowset
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationDatabaseRowsetPages extends Pages\DatabaseRowsetPages implements Library\ObjectMultiton
{
    public function __construct(Library\ObjectConfig $config )
    {
        parent::__construct($config);

        //TODO : Inject raw data using $config->data
        $pages = $this->getObject('com:pages.model.pages')
            ->published(true)
            ->getRowset();

        $this->merge($pages);
    }

    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->identity_column = 'id';
        parent::_initialize($config);
    }

    public function getPage($id)
    {
        $page = $this->find($id);
        return $page;
    }
}