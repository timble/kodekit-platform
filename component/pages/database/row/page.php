<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Modules Database Row
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseRowPage extends Library\DatabaseRowTable
{
    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        if(isset($config->state) && $config->state->type)
        {
            $this->type      = $config->state->type['name'];
            $this->link_url  = http_build_query($config->state->type, '');
        }
    }

    public function getType()
    {
        return $this->type;
    }
}