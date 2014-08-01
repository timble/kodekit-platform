<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

use Nooku\Library;
use Nooku\Component\Articles;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Articles
 */
 class ArticlesBootstrapper extends Articles\Bootstrapper
{
     protected function _initialize(Library\ObjectConfig $config)
     {
         $config->append(array(
             'aliases'  => array(
                 'com:articles.model.tags'            => 'com:tags.model.tags',
                 'com:articles.model.categories'      => 'com:categories.model.categories',
                 'com:articles.controller.attachment' => 'com:attachments.controller.attachment',
             )
         ));

         parent::_initialize($config);
     }
}