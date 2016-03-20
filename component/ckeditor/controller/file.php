<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-ckeditor for the canonical source repository
 */

namespace Kodekit\Component\Ckeditor;

use Kodekit\Library;
use Kodekit\Component\Files;

/**
 * File Controller
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Kodekit\Component\Ckeditor
 */
class ControllerFile extends Files\ControllerFile
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'model'   => 'com:files.model.files',
        ));
        parent::_initialize($config);

    }
}
