<?php
/**
 * @package     Nooku_Server
 * @subpackage  Terms
 * @copyright	Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license		GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link		http://www.nooku.org
 */

use Nooku\Library;
use Nooku\Component\Tags;

/**
 * Tag Controller Class
 *
 * @author    	Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @package     Nooku_Server
 * @subpackage  Tags
 */
abstract class TagsControllerTag extends Tags\ControllerTag
{
    public function getRequest()
    {
        $request = parent::getRequest();

        $request->query->access    = $this->getUser()->isAuthentic();
        $request->query->published = 1;

        return $request;
    }
}