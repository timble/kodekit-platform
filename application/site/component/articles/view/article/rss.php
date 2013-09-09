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
    public function render()
    {
        $params = $this->getObject('application')->getParams();

        echo '<?xml version="1.0" encoding="utf-8" ?>';

        if(is_numeric($this->getModel()->getState()->id)){
            $article = $this->getModel()->getRow();

            if ($article->isAttachable()) {
                $this->attachments($article->getAttachments());
            }

            if ($article->isTaggable()) {
                $this->tags($article->getTags());
            }

            if($params->get('commentable')){
                $this->comments = $this->getComments();
            }

        }

        return parent::render();
    }

    public function getComments()
    {
        //Get the comments
        return $this->getObject('com:comments.model.comments')
            ->table('articles')
            ->row($this->getModel()->getState()->id)
            ->sort('created_on')
            ->direction('desc')
            ->getRowset();

    }
}