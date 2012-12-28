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
 * Default Controller Permission Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerPermissionDefault extends ComDefaultControllerPermissionDefault
{
    public function canRead()
    {
        $result  = true;
        $article = $this->getModel()->getRow();

        if (!$article->isNew())
        {
            if ($article->access > $this->getUser()->isAuthentic())
            {
                //If user doesn't have access to it, deny access.
                $result = false;
            }
            elseif ($article->created_by == $this->getUser()->getId())
            {
                // Users can read their own articles regardless of the state
                $result = true;
            }
            elseif ($article->published == 0 && !$this->canEdit())
            {
                // Only published articles can be read. An exception is made for editors and above.
                $result = false;
            }
            else $result = true;

            // Set article editable status.
            $article->editable = $this->canEdit();
        }
        else $result = $this->canAdd();

        return $result;
    }
}