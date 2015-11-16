<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright	Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Module Model Entity
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Nooku\Component\Pages
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