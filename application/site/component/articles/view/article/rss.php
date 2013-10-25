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
 * Articles RSS View
 *
 * @author  Arunas Mazeika <http://nooku.assembla.com/profile/arunasmazeika>
 * @package Component\Articles
 */
class ArticlesViewArticleRss extends Library\ViewRss
{
    public function setData(Library\ObjectConfigInterface $data)
    {
        if(is_numeric($this->getModel()->getState()->id))
        {
            $article = $this->getModel()->getRow();
            $params = $this->getObject('application')->getParams();

            if ($article->isAttachable()) {
                $data->attachments = $article->getAttachments();
            }

            if ($article->isTaggable()) {
                $data->tags = $article->getTags();
            }

            if ($article->isCommentable() && $params->get('commentable')) {
                $data->comments = $article->getComments();
            }
        }

        return parent::setData($data);
    }
}