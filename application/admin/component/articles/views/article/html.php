<?php
/**
 * @category    Nooku
 * @package     Nooku_Server
 * @subpackage  Articles
 * @copyright   Copyright (C) 2011 - 2012 Timble CVBA and Contributors. (http://www.timble.net).
 * @license     GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link        http://www.nooku.org
 */

/**
 * Group HTML view class.
 *
 * @author     Tom Janssens <http://nooku.assembla.com/profile/tomjanssens>
 * @category   Nooku
 * @package    Nooku_Server
 * @subpackage Articles
 */
class ComArticlesViewArticleHtml extends ComDefaultViewHtml
{
    public function render()
    {
        $article = $this->getModel()->getRow();

        if ($article->id && $article->isAttachable()) {
            $this->attachments($article->getAttachments());
        }
        
        if ($article->id && $article->isTaggable()) {
            $this->terms($article->getTerms()->terms_term_id);
        }
        
        return parent::render();
    }
}