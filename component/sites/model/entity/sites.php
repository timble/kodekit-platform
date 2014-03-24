<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
 */

namespace Nooku\Component\Sites;

use Nooku\Library;

/**
 * Site Model Entity
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Sites
 */
class ModelEntitySites extends Library\ModelEntityCollection
{       
    protected function _initialize(Library\ObjectConfig $config)
    {
        $config->append(array(
            'identity_key' => 'name'
        ));
        
        parent::_initialize($config);
    }
}