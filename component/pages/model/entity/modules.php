<?php
/**
 * Kodekit Component - http://www.timble.net/kodekit
 *
 * @copyright	Copyright (C) 2011 - 2016 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		MPL v2.0 <https://www.mozilla.org/en-US/MPL/2.0>
 * @link		https://github.com/timble/kodekit-pages for the canonical source repository
 */

namespace Kodekit\Component\Pages;

use Kodekit\Library;

/**
 * Module Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Kodekit\Component\Pages
 */
class ModelEntityModules extends Library\ModelEntityRowset
{
    public function getPositions()
    {
        //Load a unique list of module positions
        $positions = array();
        foreach($this as $module) {
            $positions[] = $module->position;
        }

        return array_unique($positions);
    }
}