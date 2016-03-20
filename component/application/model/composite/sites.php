<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-application for the canonical source repository
 */

namespace Kodekit\Component\Application;

use Kodekit\Library;

/**
 * Sites Composite Model
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Application
 */
class ModelCompositeSites extends ModelSites implements Library\ObjectSingleton
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'decorators' => array('lib:model.composite.decorator'),
        ));

        parent::_initialize($config);
    }
}