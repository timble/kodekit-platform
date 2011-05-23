<?php
/**
 * @version     $Id$
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Language Database Row Class
 *
 * @author      Ercan Ozkaya <http://nooku.assembla.com/profile/ercanozkaya>
 * @category	Nooku
 * @package     Nooku_Server
 * @subpackage  Languages
 */
class ComDefaultControllerBehaviorExecutable extends KControllerBehaviorExecutable
{  
    protected function _beforeAdd(KCommandContext $context)
    {
        return false;
    }
    
    protected function _beforeDelete(KCommandContext $context)
    {
        return false;
    }
}