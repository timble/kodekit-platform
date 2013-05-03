<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git
 */

namespace Nooku\Component\Files;

use Nooku\Library;

/**
 * Bootstrapper
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Nooku\Component\Files
 */
 class Bootstrapper extends Library\BootstrapperAbstract
{
    public function bootstrap()
    {
        $this->getClassLoader()
             ->getLocator('psr')
             ->registerNamespace('Imagine', JPATH_VENDOR.'/imagine/imagine/lib');
    }
}