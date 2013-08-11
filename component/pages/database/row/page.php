<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Pages;

use Nooku\Library;

/**
 * Page Database Row
 *
 * @author  Gergo Erdosi <http://nooku.assembla.com/profile/gergoerdosi>
 * @package Nooku\Component\Pages
 */
class DatabaseRowPage extends Library\DatabaseRowTable
{
    protected $_type;

    public function __construct(Library\ObjectConfig $config)
    {
        parent::__construct($config);

        if(isset($config->state) && $config->state->type) {
            $this->setType($config->state->type);
        }
    }

    public function setType(array $type)
    {
        $this->_type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->_type;
    }
}