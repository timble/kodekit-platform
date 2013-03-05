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
class ComArticlesViewArticlesHtml extends ComDefaultViewHtml
{
    public function render()
    {        
        $this->category_not_section = $this->getService('com://admin/articles.model.categories')
                            ->table('articles')
                            ->id($this->getModel()->getState()->category)
                            ->getRow()
                            ->parent_id;
        
        return parent::render();
    }
}