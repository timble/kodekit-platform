<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Languages;

use Nooku\Library;

/**
 * Language Controller
 *
 * @author  Johan Janssens <http://github.com/johanjanssens>
 * @package Component\Languages
 */
class ControllerLanguage extends Library\ControllerModel
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array(
                'editable', 'persistable',
            )
        ));

        parent::_initialize($config);
    }
}