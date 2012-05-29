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

        $result = false;

        $article = $this->getModel()->getItem();

        $user = JFactory::getUser();

        if ($article->access <= $user->get('aid', 0)) {
            $result = true;
        }

        return $result;
    }

    public function canEdit() {

        $article = $this->getModel()->getItem();

        $user = JFactory::getUser();

        // Users can edit their own articles.
        if ($article->created_by == $user->id) {
            $result = true;
        } else {
            $result = parent::canEdit();
        }

        return $result;
    }

    public function canBrowse() {
        return parent::canBrowse();
    }
}