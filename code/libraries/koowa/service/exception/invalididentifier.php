<?php
/**
 * @version     $Id: exception.php 4629 2012-05-06 22:11:00Z johanjanssens $
 * @package     Koowa_Service
 * @subpackage  Exception
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Service Exception Not Found Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Exception
 */
class KServiceExceptionInvalidIdentifier extends \InvalidArgumentException implements KServiceException {}