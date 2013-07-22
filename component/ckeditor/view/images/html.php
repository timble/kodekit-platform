<?php
/**
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;

/**
 * Files Html View Class
 *
 * @author      Terry Visser <http://nooku.assembla.com/profile/terryvisser>
 * @package     Nooku_Components
 * @subpackage  Ckeditor
 */

class ViewImagesHtml extends Library\ViewHtml
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'auto_assign' => false
        ));

        parent::_initialize($config);
    }
}
