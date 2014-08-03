<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Articles
 */
 class ArticlesBootstrapper extends Library\ObjectBootstrapperComponent
{
     protected function _initialize(Library\ObjectConfig $config)
     {
         $config->append(array(
             'aliases'  => array(
                 'com:articles.model.categories' => 'com:categories.model.categories',
             )
         ));

         parent::_initialize($config);
     }
}