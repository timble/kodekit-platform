<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Files
 */
 class Bootstrapper extends Library\ObjectBootstrapperComponent
{
     protected function _initialize(Library\ObjectConfig $config)
     {
         $config->append(array(
             'priority' => self::PRIORITY_LOW,
             'aliases'  => array(
                 'com:files.model.entity.directories'  => 'com:files.model.entity.folders',
                 'com:files.model.entity.directory'    => 'com:files.model.entity.folder',
             ),
             'namespaces' => array(
                 'standard' => array('Imagine' =>  JPATH_VENDOR.'/imagine/imagine/lib')
             )
         ));

         parent::_initialize($config);
     }
}