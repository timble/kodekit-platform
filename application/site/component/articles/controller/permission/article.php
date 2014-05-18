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
 * Article Controller Permission
 *
 * @author  Johan Janssens <http://nooku.assembla.com/profile/johanjanssens>
 * @package Component\Articles
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
            if ($article->access > (int) $this->getUser()->isAuthentic()) {
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