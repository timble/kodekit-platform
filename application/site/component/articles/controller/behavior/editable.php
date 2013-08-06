<?php
/**
 * @package     Koowa_Controller
 * @subpackage  Behavior
 * @copyright   Copyright (C) 2007 - 2012 Johan Janssens. All rights reserved.
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

use Nooku\Library;

/**
 * Editable Controller Behavior Class
 *
 * @author      Johan Janssens <johan@nooku.org>
 * @package     Koowa_Controller
 * @subpackage  Behavior
 */
class ArticlesControllerBehaviorEditable extends Library\ControllerBehaviorEditable
{
    /**
     * Check if the entity is lockable
     *
     * @return bool Returns TRUE if the entity is can be locked, FALSE otherwise.
     */
    public function isLockable()
    {
        $result = false;

        if($this->getView()->getLayout() == 'form') {
            $result = parent::isLockable();
        }

        return $result;
    }
}