<?php
/**
 * @version     $Id$
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates
 * @copyright   Copyright (C) 2011 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Template Controller Executable Behavior Class
 *
 * @author      Stian Didriksen <http://nooku.assembla.com/profile/stiandidriksen>
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Templates    
 */
class ComTemplatesControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
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