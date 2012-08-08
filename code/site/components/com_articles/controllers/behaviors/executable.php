<?php
/**
 * @version        $Id$
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Article Executable Controller Behavior Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerBehaviorExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canRead()
    {
        if($this->getMixer()->getIdentifier()->name != 'article')
        {
            $result  = true;
            $article = $this->getModel()->getItem();

            if (!$article->isNew())
            {
                //If user doesn't have access to it, deny access.
                if ($article->access > JFactory::getUser()->get('aid', 0)) {
                    $result = false;
                }

                // Users can read their own articles regardless of the state
                if ($article->created_by == JFactory::getUser()->id) {
                    $result = true;
                }

                // Only published articles can be read. An exception is made for editors and above.
                if ($article->state == 0 && !$this->canEdit()) {
                    $result = false;
                }
            }

            return $result;
        }

        return parent::canRead();
    }
}