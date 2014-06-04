<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Application;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Application
 */
class ApplicationBootstrapper extends Application\Bootstrapper
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $base_path = Nooku::getInstance()->getBasePath();

        $config->append(array(
            'priority' => self::PRIORITY_LOW,
            'aliases'  => array(
                'application.languages' => 'com:application.model.entity.languages',
                'application.pages'     => 'com:application.model.entity.pages',
                'application.modules'   => 'com:application.model.entity.modules',
            ),
            'identifiers' => array(
                'com:application.template.locator.component'  => array(
                    'theme_path' => $base_path.'/public/theme/bootstrap'
                ),
                'com:application.template.filter.url'  => array(
                    'aliases' => array('/assets/application/' => '/theme/bootstrap/')
                )
            )
        ));

        parent::_initialize($config);
    }
}