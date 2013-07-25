<?php
/**
 * @package     Nooku_Server
 * @subpackage  Ckeditor
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;


/**
 * Image Controller Class
 *
 * @author      Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 */
class ControllerArticle extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:articles.model.articles',
        ));

        parent::_initialize($config);
    }
}
