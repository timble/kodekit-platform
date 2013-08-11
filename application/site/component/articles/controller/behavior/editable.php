<?php
/**
 * Nooku Framework - http://www.nooku.org
 *
 * @copyright	Copyright (C) 2011 - 2013 Johan Janssens and Timble CVBA. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		git://git.assembla.com/nooku-framework.git for the canonical source repository
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