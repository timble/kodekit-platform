<?php
/**
 * Nooku Platform - http://www.nooku.org/platform
 *
 * @copyright      Copyright (C) 2011 - 2014 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           https://github.com/nooku/nooku-platform for the canonical source repository
 */

namespace Nooku\Platform\Comments;

use Nooku\Library;
use Nooku\Component\Comments;

/**
 * Comment controller class.
 *
 * @author  Terry Visser <http://github.com/terryvisser>
 * @package Component\Comments
 */
abstract class ControllerComment extends Comments\ControllerComment
{
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'behaviors' => array('editable'),
        ));

        parent::_initialize($config);
    }
}