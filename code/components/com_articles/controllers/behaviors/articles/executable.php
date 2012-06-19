<?php
/**
 * @version        $Id$
 * @category       Nooku
 * @package        Nooku_Server
 * @subpackage     Articles
 * @copyright      Copyright (C) 2009 - 2012 Timble CVBA and Contributors. (http://www.timble.net)
 * @license        GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link           http://www.nooku.org
 */

/**
 * Article executable controller behavior class.
 *
 * @author     Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesControllerBehaviorArticleExecutable extends ComDefaultControllerBehaviorExecutable
{
    public function canRead() {

        $result = true;

        $article = $this->getModel()->getItem();
        $user    = JFactory::getUser();

        if (!$article->isNew()) {
            // First things first. If user doesn't have access to it, deny access.
            if ($article->access > $user->get('aid', 0)) {
                $result = false;
            } elseif ($article->created_by == $user->id) {
                // Users can read their own articles regardless of the state.
                $result = true;
            } elseif ($article->state == 0 && !$this->canEdit()) {
                // Only published articles can be read. An exception is made for editors and above.
                $result = false;
            }
        }
        return $result;
    }
}