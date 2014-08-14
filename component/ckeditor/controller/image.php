<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Ckeditor;

use Nooku\Library;
use Nooku\Component\Files;

/**
 * Image Controller
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Nooku\Component\Ckeditor
 */
class ControllerImage extends Files\ControllerImage
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:files.model.images',
        ));

        parent::_initialize($config);
    }
}
