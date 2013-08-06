<?php
/**
 * @package     Nooku_Server
 * @subpackage  Application
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Application Bootstrapper
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Nooku_Server
 * @subpackage  Application
 */
class ApplicationBootstrapper extends Library\BootstrapperAbstract
{
     protected function _initialize(Library\ObjectConfig $config)
     {
         $config->append(array(
             'priority' => Library\BootstrapperChain::PRIORITY_LOW,
         ));

         parent::_initialize($config);
     }

    public function bootstrap()
    {
        $manager = $this->getObjectManager();

        $manager->registerAlias('application'           , 'com:application.dispatcher.http');
        $manager->registerAlias('application.extensions', 'com:application.database.rowset.extensions');
        $manager->registerAlias('application.languages' , 'com:application.database.rowset.languages');
        $manager->registerAlias('application.pages'     , 'com:application.database.rowset.pages');
        $manager->registerAlias('application.modules'   , 'com:application.database.rowset.modules');

        $manager->registerAlias('lib:database.adapter.mysql', 'com:application.database.adapter.mysql');
    }
}