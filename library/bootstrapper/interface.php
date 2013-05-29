<?php
/**
 * @package     Koowa_Bootstrapper
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

namespace Nooku\Library;

/**
 * Object Bootstrapper Interface
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Bootstrapper
 */
interface BootstrapperInterface
{
    /**
     * Perform the bootstrapping
     *
     * @return void
     */
    public function bootstrap();
}