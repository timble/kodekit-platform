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
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationBootstrapper extends Library\ObjectBootstrapperComponent
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'priority' => self::PRIORITY_LOW,
            'aliases'  => array(
                'application'                    => 'com:application.dispatcher.http',
                'application.extensions'         => 'com:application.database.rowset.extensions',
                'application.languages'          => 'com:application.database.rowset.languages',
                'application.pages'              => 'com:application.database.rowset.pages',
                'application.modules'            => 'com:application.database.rowset.modules',
                'lib:database.adapter.mysql'     => 'com:application.database.adapter.mysql',
                'lib:template.locator.component' => 'com:application.template.locator.component',
            )
        ));

        parent::_initialize($config);
    }
}