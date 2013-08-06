<?php
/**
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

use Nooku\Library;

/**
 * Article Controller Permission Class
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ArticlesControllerPermissionArticle extends ApplicationControllerPermissionAbstract
{
    public function canRead()
    {
        $result  = true;
        $article = $this->getModel()->getRow();

        if (!$article->isNew())
        {
            //If user doesn't have access to it, deny access.
            if ($article->access > $this->getUser()->isAuthentic()) {
                $result = false;
            }

            // Only published articles can be read. An exception is made for editors and above.
            if ($article->published == 0 && !$this->canEdit()) {
                $result = false;
            }

            // Users can read their own articles regardless of the state
            if ($article->created_by == $this->getUser()->getId()) {
                $result = true;
            }
        }

        return $result;
    }
}